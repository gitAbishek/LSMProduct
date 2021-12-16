import axios from 'axios';

const http = axios.create({
	//@ts-ignore
	baseURL: _MASTERIYO_.rootApiUrl,
	headers: {
		'Content-Type': 'application/json',
		//@ts-ignore
		'X-WP-Nonce': _MASTERIYO_.nonce,
	},
});

export default http;
