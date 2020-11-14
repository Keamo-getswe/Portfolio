from flask import (
    Blueprint, flash, g, redirect, render_template, request, session, url_for
)
import WingWing.db as db
import re

bp = Blueprint('hashtags', __name__, url_prefix='/hashtags')

@bp.route('/', methods=['GET', 'POST'])
def display_hashtags():
    tweets = []
    if request.method == 'GET':
        hashtag = request.args['hashtag']
        tweets = db.get_tweets_by_hashtag_ordered_by_time(hashtag)
        tweetsold = [
        {'body':'Hello from #narga. This is a #test!!!!',
        'time_stamp':'today',
        'num_likes': 7,
        'num_retweets': 8},
        {'body':'#another#test',
        'time_stamp':'tomorrow',
        'num_likes': 23,
        'num_retweets': 8},
        ]

    elif request.method == 'POST':
        hashtag = request.form['hashtag']
        tweets = db.get_tweets_by_hashtag_ordered_by_time(hashtag)

    trending = db.get_top_5_hashtags()
    used_by_friends = []

    if g.user is not None:
        username = session['username']
        used_by_friends = db.get_top_5_user_hashtags(username)
        stats = db.user_stats(username)
        g.stats = stats

    trendingold = ['elections', 'winter']
    used_by_friendsold = ['study', 'long nights']

    #testing##############################
    tweetsold = [
    {'body':'Hello from #narga. This is a #test!!!!',
    'time_stamp':'today',
    'num_likes': 7,
    'num_retweets': 8},
    {'body':'#another#test',
    'time_stamp':'tomorrow',
    'num_likes': 23,
    'num_retweets': 8},
    ]
    for tweet in tweets:
        tweet['body'] = add_links(tweet['body'])

    statsold = {
    'followers': 8,
    'following': 8,
    'likes' : 8,
    'retweets' : 8,
    'tweets': 8
    }

    ######################################
    return render_template('hashtags/hashtag_network.html', hashtag=hashtag,
                           tweets=tweets, trending=trending,
                           used_by_friends=used_by_friends)

def add_links(string):
    pattern = re.compile(r'(#(([a-zA-Z0-9_@$+\-&%=])+))')
    linked_str = pattern.sub(r"<a href=/hashtags?hashtag=\2>\1</a>", string)
    return linked_str

def retrieve_tags(string):
    ini_tags = set([i[1:] for i in string.split() if i.startswith("#")])
    pattern = re.compile(r'([a-zA-Z0-9_@$+\-&%=])+')
    tags = []
    for tag in ini_tags:
        stripped = pattern.search(tag)
        tags.append(stripped.group())
    return tags
