import axios from 'axios';
import urls from '../constants/urls';

const configProduction = {
	//@ts-ignore
	baseURL: _MASTERIYO_.rootApiUrl,
	headers: {
		'Content-Type': 'application/json',
		//@ts-ignore
		'X-WP-Nonce': _MASTERIYO_.nonce,
	},
};

const configDevelopment = {
	baseURL: urls.base,
	headers: {
		'Content-Type': 'application/json',
	},
	auth: {
		username: process.env.WORDPRESS_USERNAME || '',
		password: process.env.WORDPRESS_PASSWORD || '',
	},
};

const config = configProduction;

const http = axios.create(config);

export default http;
