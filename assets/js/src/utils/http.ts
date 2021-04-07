import axios from 'axios';

import { baseUrl } from './../constants/urls';

const http = axios.create({
	baseURL: baseUrl,
	headers: {
		'Content-Type': 'application/json',
	},
	auth: {
		username: process.env.username || '',
		password: process.env.password || '',
	},
});

export default http;
