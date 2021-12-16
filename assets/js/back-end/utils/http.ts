import axios from 'axios';

const configProduction = {
	//@ts-ignore
	baseURL: _MASTERIYO_.rootApiUrl,
	headers: {
		'Content-Type': 'application/json',
		//@ts-ignore
		'X-WP-Nonce': _MASTERIYO_.nonce,
	},
};

const config = configProduction;

const http = axios.create(config);

export default http;
