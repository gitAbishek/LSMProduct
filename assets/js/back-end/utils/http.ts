import http from '@wordpress/api-fetch';

//@ts-ignore
http.use(http.createNonceMiddleware(_MASTERIYO_.nonce));

//@ts-ignore
http.use(http.createRootURLMiddleware(_MASTERIYO_.rootApiUrl));

export default http;
