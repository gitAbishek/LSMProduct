import http from '@wordpress/api-fetch';
import localized from './global';

http.use(http.createNonceMiddleware(localized.nonce));
http.use(http.createRootURLMiddleware(localized.rootApiUrl));

export default http;
