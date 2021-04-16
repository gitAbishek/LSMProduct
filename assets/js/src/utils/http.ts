import axios from 'axios';

import urls from '../constants/urls';
import { isProduction } from './helper';

const configProduction = {
	//@ts-ignore
	baseURL: masteriyo.rootApiUrl,
	headers: {
		'Content-Type': 'application/json',
		//@ts-ignore
		'X-WP-Nonce': masteriyo.nonce,
	},
};

const configDevelopment = {
	baseURL: urls.base,
	headers: {
		'Content-Type': 'application/json',
	},
	auth: {
		username: process.env.username || '',
		password: process.env.password || '',
	},
};

const config = isProduction ? configProduction : configDevelopment;

const http = axios.create(config);

export default http;
