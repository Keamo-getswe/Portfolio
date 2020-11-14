from flask import (
    Blueprint, flash, g, redirect, render_template, request, session, url_for
)
import WingWing.db as db
import WingWing.auth as auth
import WingWing.hashtags as hashtags
import sys

bp = Blueprint('home', __name__)


@bp.route('/', methods=('GET', 'POST'))
def index():
    if request.method == 'POST':

        # Login request
        if request.form.get('login_username') is not None:
            username = request.form['login_username']
            password = request.form['password']
            auth.login(username, password)

        # Register request
        elif request.form.get('password_1') is not None:
            username = request.form['username']
            password_1 = request.form['password_1']
            password_2 = request.form['password_2']
            auth.register(username, password_1, password_2)

        elif request.form.get('retweet') is not None:
            id = request.form['retweet']
            db.retweet(g.user, id)

        elif request.form.get('like') is not None:
            id = request.form['like']
            db.like_a_tweet(g.user, id)

        elif request.form.get('tweet') is not None:
            tweet = request.form['tweet']
            caller = request.form['caller']
            make_tweet(tweet, caller)

    suggestions = []
    following = []
    trending = []
    if g.user is not None:
        username = session['username']
        tweets = db.get_tweets_of_following_and_user(username)
        tweetsold = [
        {'body':'Hello',
        'time_stamp':'today',
        'num_likes': 7,
        'num_retweets': 8,
        'user': 'person_1',
        'id':1},
        {'body':'Hellooo',
        'time_stamp':'tomorrow',
        'num_likes': 23,
        'num_retweets': 8,
        'user': 'person_2',
        'id':2}
        ]
        following = db.get_following(username)
        followingold = [
        {'username':'person_3',
        'bio':'user\'s bio',
        'photo': 'profile_pic'},
        {'username':'person_4',
        'bio':'user\'s bio',
        'photo': 'profile_pic'},
        ]
        ftweets = {}
        for person in following:
            ftweets[person['username']] = db.get_users_tweets(person['username'])
            for tweet in ftweets[person['username']]:
                tweet['body'] = hashtags.add_links(tweet['body'])

        g.ftweets = ftweets
        suggestions = db.get_follower_suggestions(username)
        suggestionsold = [
        {'username':'person_1',
        'bio':'pick me',
        'photo': 'profile_pic',
        'likes': 8,
        'tweets': 10},
        {'username':'person_2',
        'bio':'no, pick me',
        'photo': 'profile_pic',
        'likes': 8,
        'tweets': 10}
        ]
        stats = db.user_stats(username)
        statsold = {
        'followers': 8,
        'following': 8,
        'likes' : 8,
        'retweets' : 8,
        'tweets': 8
        }
        g.stats = stats
        trending = db.get_top_5_hashtags()
    else:
        tweets = db.get_tweets()
        trending = db.get_top_5_hashtags()

    for tweet in tweets:
        tweet['body'] = hashtags.add_links(tweet['body'])

    return render_template('home/index.html', suggestions=suggestions,
                           following=following, tweets=tweets,
                           trending=trending)

def make_tweet(tweet, caller):
    tags = hashtags.retrieve_tags(tweet)
    db.make_a_tweet(g.user, tweet, tags)
    if caller == 'home':
        return redirect(url_for('home.index'))

    else:
        return redirect(url_for('profile.profile'))
