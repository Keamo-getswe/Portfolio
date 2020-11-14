from flask import (
    Blueprint, flash, g, redirect, render_template, request, session, url_for
)
from werkzeug.security import check_password_hash, generate_password_hash

import WingWing.db as db
import re, sys

bp = Blueprint('auth', __name__, url_prefix='/auth')

#---------------------------------------------------------------------------
#   Register
#--------------------------------------------------------------------------

def register(username, password_1, password_2):
    error_msg = None

    if not password_match(password_1, password_2):
        error_msg = 'Your passwords did not match!'
        error_msg = error_msg + ' Please try again.'

    elif db.user_exists(username):
        #username already taken
        error_msg = 'Sorry! This username already exists.'
        error_msg = error_msg + ' Please provide a different username.'

    elif not validate_password_strength(password_1):
        error_msg = 'password not good enough...'

    if error_msg is None:
        db.new_user(username, generate_password_hash(password_1))
        flash("Registration successful. Please log in to continue.")
        return redirect(url_for('home.index'))

    else:
        flash(error_msg)
        return redirect(url_for('home.index'))

#---------------------------------------------------------------------------
#   Login
#---------------------------------------------------------------------------

def login(username, password):
    error_msg = None

    if not db.user_exists(username):
        error_msg = 'No such user exists. Please register.'

    elif not check_password_hash(db.get_user_password(username), password):
        error_msg = 'Wrong password. Please try again.'

    if error_msg is None:
        #login was successful
        session.clear()
        session['username'] = username
        g.user = username
        return redirect(url_for('home.index'))

    else:
        flash(error_msg)
        return redirect(url_for('home.index'))

#---------------------------------------------------------------------------
#   Logout
#---------------------------------------------------------------------------

@bp.route('/logout')
def logout():
    session.clear()
    return redirect(url_for('home.index'))

#---------------------------------------------------------------------------
#   Helper functions
#---------------------------------------------------------------------------

# get login credentials before rendering any page
@bp.before_app_request
def load_user():
    username = session.get('username')

    if username is not None:
        user = db.get_user(username)
        g.user = username
        g.bio = user[0]['bio']
        g.photo = user[0]['photo']
        #g.bio = 'change in load_user'
        #g.photo = 'photo'

    else:
        g.user = None

# Validate password strength
# Regular expression derived with guidance from
# http://regexlib.com/Search.aspx?k=Password&AspxAutoDetectCookieSupport=1
def validate_password_strength(password):
    if re.match('^.*(?=.*[!@#$%^&*+=])(?!.*\s)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.{8,}).*$', password):
        return True

    else:
        return False

# Checks that passwords given in registration match
def password_match(password_1, password_2):
    return password_1 == password_2

def update_password(pw_1, pw_2):
    error_msg = None

    if not password_match(pw_1, pw_2):
        error_msg = 'Your passwords did not match!'
        error_msg = error_msg + ' Please try again.'

    elif not validate_password_strength(pw_1):
        error_msg = 'password not good enough...'

    if error_msg is None:
        username = g.user
        db.update_password(username, generate_password_hash(pw_1))
        flash("Successfully updated password.")
        return redirect(url_for('home.index'))

    else:
        flash(error_msg)
        return redirect(url_for('home.index'))
