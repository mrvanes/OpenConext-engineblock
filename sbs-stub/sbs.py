#!/usr/bin/env python
import json
import logging
import secrets

from flask import Flask, Response, request, render_template

logging.getLogger().setLevel(logging.DEBUG)
logging.getLogger('flask_pyoidc').setLevel(logging.ERROR)
logging.getLogger('oic').setLevel(logging.ERROR)
logging.getLogger('jwkest').setLevel(logging.ERROR)
logging.getLogger('urllib3').setLevel(logging.ERROR)
logging.getLogger('werkzeug').setLevel(logging.ERROR)

app = Flask(__name__, template_folder='templates', static_folder='static')

nonces = {}


def debug(request):
    for header in request.headers:
        logging.debug(header)
    for key, value in request.form.items():
        logging.debug(f'POST {key}: {value}')


@app.route('/authz', methods=['POST'])
def api():
    logging.debug('-> /authz')
    debug(request)

    uid = request.form.get('uid')
    continue_url = request.form.get('continue_url')

    nonce = secrets.token_urlsafe()
    nonces[nonce] = (uid, continue_url)

    response = Response(status=200)
    body = {
        'msg': 'interrupt',
        'nonce': nonce
    }

    logging.debug(f'<- {body}')
    response.data = json.dumps(body)

    return response


@app.route('/interrupt', methods=['GET'])
def interrupt():
    logging.debug('-> /interrupt')
    nonce = request.args.get('nonce')
    (uid, continue_url) = nonces.get(nonce, ('unknown', '/'))
    response = render_template('interrupt.j2', uid=uid, url=continue_url)

    return response


@app.route('/entitlements', methods=['POST'])
def entitlements():
    logging.debug('-> /entitlements')
    debug(request)

    nonce = request.form.get('nonce')
    (uid, _) = nonces.pop(nonce)

    response = Response(status=200)
    body = {
        'entitlements': [
            uid,
            nonce,
            'urn:foobar',
        ]
    }

    logging.debug(f'<- {body}')
    response.data = json.dumps(body)

    return response


if __name__ == "__main__":
    app.run(host='0.0.0.0', port=12345, debug=True)
