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
