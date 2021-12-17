import http from '@wordpress/api-fetch';

// const http = axios.create({
// 	//@ts-ignore
// 	baseURL: _MASTERIYO_.rootApiUrl,
// 	headers: {
// 		'Content-Type': 'application/json',
// 		//@ts-ignore
// 		'X-WP-Nonce': _MASTERIYO_.nonce,
// 	},
// });

//@ts-ignore
http.use(http.createNonceMiddleware(_MASTERIYO_.nonce));

//@ts-ignore
http.use(http.createRootURLMiddleware(_MASTERIYO_.rootApiUrl));

export default http;
