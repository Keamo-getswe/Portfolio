from flask import (
    Blueprint, flash, g, redirect, render_template, request, session, url_for,
    current_app
)
from werkzeug.security import (
    check_password_hash, generate_password_hash
)
from werkzeug.utils import secure_filename
import WingWing.db as db
import WingWing.auth as auth
import WingWing.hashtags as hashtags
import sys
import os

bp = Blueprint('profile', __name__, url_prefix='/profile')

ALLOWED_EXTENSIONS = set(['png', 'jpg', 'jpeg'])
basedir = os.path.abspath(os.path.dirname(__file__))
#---------------------------------------------------------------------------
#   Profile view
#--------------------------------------------------------------------------

@bp.route('/user', methods=('GET', 'POST'))
def profile():
    username = session['username']
    if request.method == 'POST':
        if request.form.get('tweet') is not None:
            pass

        # Follow a suggested person
        elif request.form.get('to_follow') is not None:
            to_follow = request.form['to_follow']
            db.follow_user(username, to_follow)

        elif request.form.get('bio') is not None:
            #Update User's profile information
            bio = request.form['bio']

            file = request.files['file']
            if file.filename == '':
                flash('No selected file')
                return redirect(request.url)

            g.photo = None
            g.bio = bio

            if file and allowed_file(file.filename):
                filename = secure_filename(file.filename)
                path = os.path.join(basedir, current_app.config['UPLOAD_FOLDER'],
                                    filename)
                file.save(path)

                path = os.path.join("..", current_app.config['UPLOAD_FOLDER'], filename)
                g.photo = path
                db.update_user(username, path, bio)
            else:
                flash("Upload failed.")

            if g.photo is None:
                db.update_user_bio(username, bio)

        elif request.form.get('search') is not None:
            s_username = request.form['search']
            search_result = db.get_user(s_username)
            ftweets = {}
            ftweets[s_username] = db.get_users_tweets(s_username)
            for tweet in ftweets[s_username]:
                tweet['body'] = hashtags.add_links(tweet['body'])
            g.ftweets = ftweets

            if search_result == []:
                flash(s_username + " is not a registered user.")
            else:
                g.search_result = search_result[0]

        elif request.form.get('new_password_1') is not None:
            password_1 = request.form['new_password_1']
            password_2 = request.form['new_password_2']
            auth.update_password(password_1, password_2)

    tweets = db.get_users_tweets(username)
    for tweet in tweets:
        tweet['body'] = hashtags.add_links(tweet['body'])
    #tweets = [
    #{'body':'Hello',
    #'time_stamp':'today',
    #'num_likes': 7,
    #'num_retweets': 8,
    #'id': '1'},
    #{'body':'Hellooo',
    #'time_stamp':'tomorrow',
    #'num_likes': 23,
    #'num_retweets': 8,
    #'id': '2'},
    #]
    stats = db.user_stats(username)
    statsold = {
    'followers': 8,
    'following': 8,
    'likes' : 8,
    'retweets' : 8,
    'tweets': 8
    }
    following = db.get_followers(username)
    g.stats = stats
    return render_template('profile/user_profile.html', tweets=tweets,
                           following = following)

#-----------------------------------------------------------------------------
#   Display retweets
#-----------------------------------------------------------------------------
@bp.route('/retweets', methods=('POST', 'GET'))
def retweets():
    if request.method == 'GET':
        id = request.args['id']

        users = db.get_users_who_retweeted(id)
        html = "<h5><i class='fas fa-retweet'></i>Rechirped By</h5>"
        html += "<ul>"
        for user in users:
            html += "<li>"
            html += "@" + user['username']
            html += "</li><br>"

        html += "</ul>"
        return html

#------------------------------------------------------------------------------
#   Network
#------------------------------------------------------------------------------
@bp.route('/network')
def display_network():
    username = session['username']
    stats = db.user_stats(username)
    g.stats = stats
    db.create_json_file()
    return render_template('profile/user_network.html')

def allowed_file(filename):
    return '.' in filename and \
           filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS
