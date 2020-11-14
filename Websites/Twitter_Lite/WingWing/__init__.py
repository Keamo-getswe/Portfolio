import os

from flask import Flask

UPLOAD_FOLDER = './static/uploads'

def create_app():
    app = Flask(__name__, instance_relative_config=True)
    app.config.from_mapping(SECRET_KEY='under_construction')

    app.config.from_pyfile('config.py', silent=True)
    app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

    try:
        os.makedirs(app.instance_path)
    except OSError:
        pass

    from . import db
    db.setup_connection_close_using(app)

    # register all authentication views: login, logout, register
    from . import auth
    app.register_blueprint(auth.bp)

    # register all profile views: user profile, user's network
    from . import profile
    app.register_blueprint(profile.bp)

    # register page displaying hashtags
    from . import hashtags
    app.register_blueprint(hashtags.bp)

    from . import home
    app.register_blueprint(home.bp)
    app.add_url_rule('/', endpoint='index')

    return app
