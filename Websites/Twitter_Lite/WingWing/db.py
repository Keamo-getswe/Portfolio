from flask import current_app, g, Response, url_for
from py2neo import Graph, Node, Relationship
from neo4j.v1 import GraphDatabase, basic_auth
from os.path import normpath, join, abspath
import sys, os
import uuid
from py2neo import Graph, NodeMatcher
import json
import datetime
from json import dumps

driver = GraphDatabase.driver("bolt://hobby-fbencgjedgejgbkecbebpccl.dbs.graphenedb.com:24787",
 auth=basic_auth("dbuser", "b.nAnYevLeizNP.AddgjBGB2P7eWPuv"))

# The current number of suggestions that you will get
suggestions_popular_num = 10

def get_db():
    """
    Get a database session to run queries
    """
    if 'neo4j_db' not in g:
        g.neo4j_db = driver.session()
        #g.neo4j_db.run("CREATE (u:User {username:'username', password:'password'}) RETURN u")
    else:
        g.neo4j_db = driver.session()
    return

def close_db(error):
    """
    Close the database sessions
    """
    neo4j_db = g.pop('neo4j_db', None)
    if neo4j_db is not None:
        neo4j_db.close()

def setup_connection_close_using(app):
    app.teardown_appcontext(close_db)

#------------------------------------------------------------------------------
#    Users
#------------------------------------------------------------------------------
def new_user(username, password):
    """
    Add a new user to the neo database
    """
    get_db()
    default_photo = "../static/icons/avatar.png"
    query = "CREATE (u:User {id:'%s' ,username:'%s', password:'%s', photo:'%s'}) RETURN u" % (get_id(), username, password, default_photo)
    g.neo4j_db.run(query)

def update_user(username, photo, bio):
    """
    Update a user's photo and bio
    """
    get_db()
    cypher = "MERGE (u:User {username: '" + username + "'}) SET u.photo = '" + photo + "', u.bio = '" + bio + "'"
    g.neo4j_db.run(cypher)

def update_user_bio(username, bio):
    """
    Update a user's bio
    """
    get_db()
    cypher = "MERGE (u:User {username: '" + username + "'}) SET u.bio = '" + bio + "'"
    g.neo4j_db.run(cypher)

def update_user(username, photo, bio):
    """
    Update a user's photo and bio
    """
    get_db()
    cypher = "MERGE (u:User {username: '" + username + "'}) SET u.photo = '" + photo + "', u.bio = '" + bio + "'"
    g.neo4j_db.run(cypher)

def user_exists(username):
    """
    Returns true if a username exists and false otherwise
    """
    get_db()
    query = "MATCH (u:User {username:'%s'}) RETURN u" % (username)
    result = g.neo4j_db.run(query)
    if result.peek() == None:
        return False
    else:
        return True

def get_user_password(username):
    """
    If user exists, returns user's password and None otherwise
    """
    get_db()
    query = "MATCH (u:User {username:'%s'}) RETURN u.password AS pw" % username
    result = g.neo4j_db.run(query)
    for record in result:
        password = record["pw"]
    return password

def get_user(username):
    """
    Get a user's full details in the form of a dictionary
    """
    session = driver.session()
    cypher = "MATCH(u: User {username: '" + username + "'}) RETURN u"
    result = session.run(cypher)
    session.close()
    return [serialize_user(record['u']) for record in result]

def most_popular_users():
    """
    Get the most followed users
    """
    session = driver.session()
    cypher = "MATCH (u:User) WITH u, SIZE(()-[:FOLLOWS]->(u)) as foll"
    cypher = cypher + " RETURN u ORDER BY foll DESC LIMIT 10" + str(suggestions_popular_num)
    result = session.run(cypher)
    session.close()
    return [serialize_popular_user(record['u']) for record in result]

group_value = 1

def get_all_users():
    session = driver.session()
    cypher = "MATCH (n:User) RETURN n"
    result = session.run(cypher)
    return [serialize_user(record['n']) for record in result]

def update_password(username, new_password):
    """
    Update the password of a user
    """
    get_db()
    cypher = "MERGE (u:User {username: '" + username + "'}) SET u.password = '" + new_password + "'"
    g.neo4j_db.run(cypher)

def user_stats(username):
    """
    Return the number of: tweets made by user, retweets of user's tweets, likes of
    user's tweets, people following user, people user is following
    """
    followers_stat = number_of_followers(username)
    following_stat = number_of_following(username)
    likes_stat = get_num_of_likes(username)
    retweet_stat = get_num_of_retweets(username)
    tweet_stat = get_num_of_tweets(username)
    stats = {}
    stats['followers'] = followers_stat['followers']
    stats['following'] = following_stat['following']
    stats['likes'] = likes_stat['likes']
    stats['retweets'] = retweet_stat['retweets']
    stats['tweets'] = tweet_stat['tweets']
    return stats

def get_follower_suggestions(username):
    """
    Returns top follower suggestions for a user based on tweet and likes
    ordering
    """
    # if the user is not following anyone return most popular users
    if is_following_anyone(username) == False:
            return most_popular_users()

    cypher = "MATCH (user:User {username:'" + username + "'})-[:FOLLOWS*2]->(foaf:User)"
    cypher = cypher + " WHERE NOT((user)-[:FOLLOWS]->(foaf)) AND NOT foaf.username = '" + username + "'WITH foaf,"
    cypher = cypher + " SIZE((foaf)-[:TWEETED]->(:Tweet)<-[:LIKED]-(:User)) as num_likes,"
    cypher = cypher + " SIZE((foaf)-[:TWEETED]->(:Tweet)) as num_tweets"
    cypher = cypher + " ORDER BY num_likes DESC, num_tweets DESC"
    cypher = cypher + " RETURN DISTINCT foaf, num_likes, num_tweets"
    session = driver.session()
    result = session.run(cypher)
    session.close()
    return [serialize_user_suggestions(record['foaf'], record['num_likes'], record['num_tweets']) for record in result]

def get_follower_suggestions_no_ordering(username):
    """
    Returns top follower suggestions for a user
    """
    # if the user is not following anyone return most popular users
    if is_following_anyone(username):
        return most_popular_users()

    following_dict = get_following(username)
    suggestions = []

    for following in following_dict:
        temp_dict = following(following['username'])
        for f in temp_dict:
            if not not_follwowing(username, f['username']):
                suggestions.append(f['username'])

    return suggestions

#------------------------------------------------------------------------------
#    Following
#------------------------------------------------------------------------------

def follow_user(follower, following):
    """
    Makes a user a follower to another user
    """
    session = driver.session()
    if is_following(follower, following):
        return

    cypher = "MATCH (a:User {username: '" + follower + "'}) ,(b:User {username: '"
    cypher = cypher + following + "'})  CREATE(a)-[r:FOLLOWS {time:localdatetime.realtime()}]->(b) RETURN r"
    session.run(cypher)
    session.close()

def is_following_anyone(username):
    """
    Returns whether an user is following anyone at all
    """
    session = driver.session()
    cypher = "MATCH (n)-[r:FOLLOWS]->(t) WHERE n.username = '" + username + "' RETURN COUNT(r)"
    result = session.run(cypher)
    data = result.data()
    session.close()
    if data[0]['COUNT(r)'] == 0:
        return False

    return True

def is_following(follower, following):
    """
    Returns whether a user follows another user
    """
    session = driver.session()
    cypher = "RETURN EXISTS( (:User {username: '"
    cypher = cypher + follower + "'})-[:FOLLOWS]->(:User {username: '" + following + "'}))"
    result = session.run(cypher)
    data = result.data()
    session.close()
    a = data[0]
    return next(iter(a.values()))

def number_of_followers(username):
    """
    Returns the number of followers of the user
    """
    session = driver.session()
    cypher = "MATCH(n)-[r:FOLLOWS]->(t) WHERE t.username='"
    cypher = cypher + username + "' RETURN COUNT(r) AS followers"
    result = session.run(cypher)
    data = result.data()
    session.close()
    return data[0]

def number_of_following(username):
    """
    Returns the number of everyone the user is following
    """
    session = driver.session()
    cypher = "MATCH(n)-[r:FOLLOWS]->(t) WHERE n.username='"
    cypher = cypher + username + "' RETURN COUNT(r) AS following"
    result = session.run(cypher)
    data = result.data()
    session.close()
    return data[0]

def get_following(username):
    """
    Returns everyone the user is following
    """
    session = driver.session()
    cypher = "MATCH(n)-[r:FOLLOWS]->(t) WHERE n.username='" + username + "' RETURN t"
    result = session.run(cypher)
    session.close()
    return [serialize_user(record['t']) for record in result]

def get_followers(username):
    """
    Returns the followers of a user
    """
    session = driver.session()
    cypher = "MATCH(n)-[r:FOLLOWS]->(t) WHERE t.username='" + username + "' RETURN n"
    result = session.run(cypher)
    session.close()
    return [serialize_user(record['n']) for record in result]

#------------------------------------------------------------------------------
#    Tweeting and Retweeting and Liking
#------------------------------------------------------------------------------
def create_tweet(tweet_id, body):
    """
    Create a new tweet
    """
    session = driver.session()
    cypher = "CREATE (m:Tweet {id:'" + tweet_id + "', body: '" + body + "' , time:localdatetime.realtime()}) RETURN m"
    session.run(cypher)
    session.close()

def make_a_tweet(username, tweet, hashtags):
    """
    Have a user make a tweet and link the tweet hashtags to the respective hashtags
    """
    # create the tweet
    tweet_id = get_id()
    create_tweet(tweet_id, tweet)
    link_user_to_tweet(username, tweet_id)
    #increment the hashtag totals or create the hashtag
    for tag in hashtags:
        if not hashtag_exists(tag):
            create_hashtag(tag)
            link_tweet_hashtag(tweet_id, tag)
        else :
            link_tweet_hashtag(tweet_id, tag)

def link_user_to_tweet(username, tweet_id):
    """
    Links a user to a tweet
    """
    session = driver.session()
    cypher = "MATCH (a:User),(b:Tweet) WHERE a.username = '" + username + "' AND b.id= '"
    cypher = cypher + tweet_id + "' CREATE(a)-[r:TWEETED {time:localdatetime.realtime()}]->(b) RETURN r"
    session.run(cypher)
    session.close()

def retweet(username, id):
    """
    Have a user retweets a tweet
    """
    if retweet_exists(username, id) == True:
            # relationship already exists
            return

    session = driver.session()
    cypher = "MATCH (a:User), (b:Tweet) WHERE a.username='" + username
    cypher = cypher + "' AND b.id='" + id + "' CREATE(a)-[r:RETWEETED {time:localdatetime.realtime()}]->(b) RETURN r"
    session.run(cypher)
    session.close()

def like_a_tweet(liker_username, tweet_id):
    """
    Have a user like a tweet
    """

    if like_exists(liker_username, tweet_id) == True:
            # like already exists
            return

    session = driver.session()
    cypher = "MATCH (a:User),(b:Tweet) WHERE a.username ='"
    cypher = cypher + liker_username + "' AND b.id ='" + tweet_id
    cypher = cypher + "' CREATE(a)-[r:LIKED {time:localdatetime.realtime()}]->(b) RETURN r"
    session.run(cypher)
    session.close()

def get_user_who_posted_tweet(tweet_id):
    """
    Returns the user who posted the tweet with the given tweet id
    """
    session = driver.session()
    cypher = "MATCH(n)-[r:TWEETED]->(t) WHERE t.id='" + tweet_id + "' RETURN n"
    result = session.run(cypher)
    session.close()
    u = [serialize_user(record['n']) for record in result]
    return u[0]

def get_users_tweets(username):
    """
    Returns a complete dictionary of tweet information of a all of a users tweets
    """
    session = driver.session()
    cypher = "MATCH(n)-[r:TWEETED]->(t) WHERE n.username='" + username + "' RETURN t"
    result = session.run(cypher)
    session.close()
    return [serialize_tweet(record['t']) for record in result]

def get_tweets():
    """
    Get all the tweets according to the latest
    """
    session = driver.session()
    cypher = "MATCH (t:Tweet)  RETURN t order by t.time desc"
    result = session.run(cypher)
    session.close()
    return [serialize_tweet(record['t']) for record in result]

def get_tweets_by_hashtag_ordered_by_time(hashtag):
    """
    Gets tweets that use a hashtag and orders them by time of tweeting
    """
    session = driver.session()
    cypher = "MATCH (a:Hashtag)<-[r:USED_TAG]-(t:Tweet) where a.hashtag='"
    cypher = cypher + hashtag + "' with r,t ORDER by r.time DESC RETURN t"
    result = session.run(cypher)
    session.close()
    return [serialize_tweet(record['t']) for record in result]

def get_n_tweets(num):
    """
    Get the provided number of tweets from the database
    """
    session = driver.session()
    cypher = "MATCH (n:Tweet) RETURN n LIMIT " + str(num)
    result = session.run(cypher)
    session.close()
    return [serialize_tweet(record['n']) for record in result]

def get_num_of_tweets(username):
    """
    Returns the number of tweets that a user has
    """
    session = driver.session()
    cypher = "MATCH(n)-[r:TWEETED]->(t) WHERE n.username='"
    cypher = cypher + username + "' RETURN COUNT(r) AS tweets"
    result = session.run(cypher)
    data = result.data()
    session.close()
    return data[0]

def retweet_exists(username, tweet_id):
    """
    returns 0 (no relationships) and 1 (already relationships) for retweets
    """
    session = driver.session()
    cypher = "MATCH (a)-[r:RETWEETED]->(b) WHERE a.username='" +  username + "' and b.id='" + tweet_id + "' return count(r) as torf"
    result = session.run(cypher)
    data = result.data()
    session.close()

    if data[0]['torf'] == 0:
        return False

    return True

def like_exists(username, tweet_id):
    """
    returns 0 (no relationships) and 1 (already relationships) for likes
    """
    session = driver.session()
    cypher = "MATCH (a)-[r:LIKED]->(b) WHERE a.username='" + username + "' and b.id='" + tweet_id + "' return count(r) as torf"
    result = session.run(cypher)
    data = result.data()
    session.close()

    if data[0]['torf'] == 0:
        return False

    return True

def get_num_of_retweets(username):
    """
    Returns a the number of retweets of a specific user's tweets.
    """
    session = driver.session()
    cypher = "MATCH (:User {username:'"
    cypher = cypher  + username + "'})-[:TWEETED]->(:Tweet)<-[:RETWEETED]-() RETURN COUNT(*) as retweets"
    result = session.run(cypher)
    data = result.data()
    session.close()
    return data[0]

def get_num_of_likes(username):
    """
    Returns the number of likes that a user's tweets have
    """
    session = driver.session()
    cypher = "MATCH (:User)-[:LIKED]->(:Tweet)<-[:TWEETED]-(:User {username:'"
    cypher = cypher + username + "'}) RETURN COUNT(*) AS likes"
    result = session.run(cypher)
    data = result.data()
    session.close()
    return data[0]

def get_num_of_retweets_of_tweet(id):
    """
    Returns a the number of retweets of a specific tweet.
    """
    session = driver.session()
    cypher = "MATCH(n)-[r:RETWEETED]->(t) WHERE t.id='"
    cypher = cypher + id + "' RETURN COUNT(r) AS tweet_retweets_num"
    result = session.run(cypher)
    session.close()
    data = result.data()
    return data[0]

def get_num_of_tweet_likes(id):
    """
    Returns a the number of likes of a specific tweet.
    """
    session = driver.session()
    cypher = "MATCH(n)-[r:LIKED]->(t) WHERE t.id='"
    cypher = cypher + id + "' RETURN COUNT(r) AS tweet_likes"
    result = session.run(cypher)
    session.close()
    data = result.data()
    return data[0]

def get_users_who_retweeted(tweet_id):
    """
    Returns users who tweeted this specific tweet
    """
    session = driver.session()
    # DISTINCT added
    cypher = "MATCH (n)-[r:RETWEETED]->(t) WHERE t.id='" + tweet_id + "' RETURN DISTINCT n"
    result = session.run(cypher)
    session.close()
    return [serialize_user(record['n']) for record in result]

def get_tweets_of_following(username):
    """
    Get tweets from users that the user is following (ordered according to tweet time)
    """
    session = driver.session()
    cypher = "match (n:User)-[:FOLLOWS]->(m:User)-[t:TWEETED]->(twts:Tweet)"
    cypher = cypher + " where n.username='" + username + "' return twts order by t.time desc limit 5"
    result = session.run(cypher)
    session.close()
    return [serialize_tweet(record['twts']) for record in result]

def get_tweets_of_following_and_user(username):
    """
    Get tweets from users that the user is following (ordered according to tweet time)
    and also the user's tweets
    """
    session = driver.session()
    cypher = "MATCH (:User {username:'" + username + "'})-[:FOLLOWS]->(friend:User)"
    cypher = cypher + " WITH COLLECT(friend.username) as friends"
    cypher = cypher + " MATCH (u:User)-[:TWEETED]->(t:Tweet)"
    cypher = cypher + " WHERE u.username = '" + username + "' OR u.username IN friends"
    cypher = cypher + " WITH t, SIZE((:User)-[:LIKED]->(t)) as num_likes,"
    cypher = cypher + " localdatetime.truncate( 'hour', localdatetime({ datetime:t.time })) as time"
    cypher = cypher + " ORDER BY time DESC, num_likes DESC"
    cypher = cypher + " RETURN t, time, num_likes"
    result = session.run(cypher)
    session.close()
    return [serialize_tweet(record['t']) for record in result]

#------------------------------------------------------------------------------
#    Hashtagging
#------------------------------------------------------------------------------
def hashtag_exists(tag):
    """
    Check if a hashtag already exists
    """
    get_db()
    query = "MATCH (h:Hashtag {hashtag:'%s'}) RETURN h" % (tag)
    result = g.neo4j_db.run(query)
    if result.peek() == None:
        return False
    else:
        return True

def link_tweet_hashtag(tweet_id, hashtag):
    """
    Create a relationship between a tweet and hashtag
    """
    session = driver.session()
    cypher = "MATCH (a:Tweet),(b:Hashtag) WHERE a.id='" + tweet_id
    cypher = cypher  + "' AND b.hashtag='" + hashtag + "' CREATE (a)-[r:USED_TAG]->(b) RETURN r"
    session.run(cypher)
    session.close()

def get_tweets_with_hashtag(username, hashtag):
    """
    Get all the tweets with a particular hashtag
    """
    session = driver.session()
    cypher = "MATCH (n:Tweet)-[r:USED_TAG]->(t:Hashtag) WHERE t.hashtag='"
    cypher = cypher + hashtag + "' RETURN n"
    session.run(cypher)
    session.close()
    return [serialize_hashtag(record['t']) for record in result]

def get_top_5_hashtags():
    """
    Get top 5 hashtags from the website
    """
    session = driver.session()
    cypher = "MATCH (tag:Hashtag) WITH tag, "
    cypher = cypher +  " SIZE((:Tweet)-[:USED_TAG]->(tag)) as num_used,"
    cypher = cypher + " SIZE((:User)-[:LIKED]->(:Tweet)-[:USED_TAG]->(tag)) as num_likes"
    cypher = cypher +  " ORDER BY num_likes DESC, num_used DESC"
    cypher = cypher + " RETURN DISTINCT tag, num_likes, num_used LIMIT 5"
    result = session.run(cypher)
    session.close()
    return [serialize_hashtag(record['tag']) for record in result]

def get_top_5_user_hashtags(username):
    """
    Get top 5 hashtags from a user's network
    """
    session = driver.session()
    cypher = "MATCH (:User {username:'" + username + "'})-[:FOLLOWS]->(:User)-[:TWEETED]->(:Tweet)-[:USED_TAG]->(tag:Hashtag) "
    cypher = cypher + " WITH tag, SIZE((:Tweet)-[:USED_TAG]->(tag)) as num_used,"
    cypher = cypher + " SIZE((:User)-[:LIKED]->(:Tweet)-[:USED_TAG]->(tag)) as num_likes"
    cypher = cypher + " ORDER BY num_likes DESC, num_used DESC"
    cypher = cypher + " RETURN DISTINCT tag, num_likes, num_used LIMIT 5"
    result = session.run(cypher)
    session.close()
    return [serialize_hashtag(record['tag']) for record in result]

def create_hashtag(tag):
    """
    Create a new hashtag
    """
    session = driver.session()
    cypher = "CREATE(h:Hashtag {hashtag: '" + tag + "'} ) RETURN h"
    session.run(cypher)
    session.close()

#-------------------------------------------------------------------------------
# JSON for D3.js visualisation
#-------------------------------------------------------------------------------
def create_json_file():
    basedir = os.path.abspath(os.path.dirname(__file__))
    STATIC_ROOT = normpath(join(basedir, ''))
    # clear the file
    f = open(STATIC_ROOT + "/static/miserables.json", "w+").close()
    f = open(STATIC_ROOT + "/static/miserables.json", "w+")

    f.write("{\n")
    f.write('  "nodes":[\n')
    # Users to write the users json part
    all_users = get_all_users()
    total_users = len(all_users)
    for u in range(len(all_users)):
        if u < (total_users - 1):
            f.write('    {"id": "' + str(all_users[u]['username']) + '" ,"likes":"' + str(get_num_of_likes(str(all_users[u]['username']))['likes']) + '", "group": ' + str(get_group_value()) + ' },\n')
        else:
            f.write('    {"id": "' + str(all_users[u]['username']) + '" ,"likes":"' + str(get_num_of_likes(str(all_users[u]['username']))['likes']) + '", "group": ' + str(get_group_value()) + ' }\n')

    f.write("  ],\n")
    f.write('  "links":[\n')
    i = -1
    for u in range(len(all_users)):
        followers_list = get_followers(str(all_users[u]['username']))
        num_of_followers = len(followers_list)
        for foll in range(len(followers_list)):
                i = i + 1
                if i != 0:
                    f.write(",\n")
                f.write('    {"source": "' + str(followers_list[foll]['username']) + '", "label": "following", "target": "' + str(all_users[u]['username']) + '", "value": ' + str(get_group_value()) + ' }')

    f.write("\n")
    f.write("  ]\n")
    f.write("}\n")
    f.close()

# Keeps track of the group number
def get_group_value():
    global group_value
    if group_value == 11:
        group_value = 1
        return group_value
    else:
        return_value = group_value
        group_value = group_value + 1
        return return_value


#------------------------------------------------------------------------------
# Serialize data helper functions
#------------------------------------------------------------------------------
# Used to reformat user data returned from queries
def serialize_user(user):
    bio = user['bio']
    if user['bio'] == None:
        bio = ''

    return {
        'username': user['username'],
        'password': user['password'],
        'bio': bio,
        'photo': user['photo']
    }

# Used to reformat user suggestions data returned from queries
def serialize_user_suggestions(user, likes, tweets):
    bio = user['bio']
    if user['bio'] == None:
        bio = ''

    return {
        'username': user['username'],
        'password': user['password'],
        'bio': bio,
        'photo': user['photo'],
        'num_likes':likes,
        'num_tweets': tweets
    }

# Used to reformat popular user data
def serialize_popular_user(user):
    bio = user['bio']
    if user['bio'] == None:
        bio = ''

    return {
        'username': user['username'],
        'password': user['password'],
        'bio': bio,
        'photo': user['photo'],
        'num_likes': get_num_of_likes(str(user['username']))['likes'],
        'num_tweets': get_num_of_tweets(str(user['username']))['tweets']
    }

# Used to reformat tweet data returned from queries
def serialize_tweet(tweet):
    time = str(tweet['time'])
    time = time[0:len(time)-3]
    time = datetime.datetime.strptime(time, "%Y-%m-%dT%H:%M:%S.%f").strftime("%d %b %Y %H:%M")
    return {
        'id' :tweet['id'],
        'body':tweet['body'],
        'time': time,
        'username': get_user_who_posted_tweet(tweet['id'])['username'],
        'likes': get_num_of_tweet_likes(tweet['id'])['tweet_likes'],
        'retweets': get_num_of_retweets_of_tweet(tweet['id'])['tweet_retweets_num']
    }

# Used to reformat user data returned from queries
def serialize_hashtag(hashtag):
    return {
        'hashtag': hashtag['hashtag']
    }

#------------------------------------------------------------------------------
# Utility functions
#------------------------------------------------------------------------------
def get_id():
    """
    Returns a UUID generated id
    """
    return str(uuid.uuid4())

def get_id_on_file():
    """
    Returns a unique database id value from a id.db file
    """
    f = open("database/id.db", "r")
    id_string = f.read()
    f.close()
    id = int(id_string)
    # clear the file after using the id
    f = open("database/id.db", "w+").close()
    f = open("database/id.db", "w+")
    new_id = id + 1
    # set the new id value
    f.write(str(new_id))
    f.close()
    return id

def debugging(message):
    f = open("database/debugging.out", "w+")
    f.write(str(message))
    f.close()
