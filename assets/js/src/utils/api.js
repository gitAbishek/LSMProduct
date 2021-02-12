import axios, { Method } from 'axios';

import urls from '../constants/urls';

const makeRequest = async (url, method, data, headers) => {
	const response = await axios({
		url,
		method,
		data,
		headers,
	});

	return response;
};
axios.interceptors.request.use(async function (config) {
	config.headers = {
		...config.headers,
		'Content-Type': 'application/json',
	};

	config.auth = {
		...config.auth,
		username: 'sethstha',
		password: 'Password@123',
	};
	return config;
});

export const fetchCourses = async () => {
	return makeRequest(urls.courses, 'GET').then((response) => response.data);
};
