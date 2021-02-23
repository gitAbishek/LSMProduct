import { isDevelopment, isProduction } from './helper';

import axios from 'axios';
import urls from '../constants/urls';

axios.interceptors.request.use(async function (config) {
	config.headers = {
		...config.headers,
		'Content-Type': 'application/json',
	};

	// Sets nonce to run API on WordPress Dashboard
	if (isProduction()) {
		config.headers = {
			...config.headers,
			//@ts-ignore
			'X-WP-Nonce': masteriyo.nonce,
		};
	}

	// Basic Auth for the local development, required basic auth plugin
	if (isDevelopment()) {
		config.auth = {
			...config.auth,
			username: process.env.username || '',
			password: process.env.password || '',
		};
	}
	return config;
});

export const fetchCourses = async () => {
	return axios.get(urls.courses).then((response) => response.data);
};

export const fetchCourse = async (id: any) => {
	return axios
		.get(urls.course.replace(':id', id))
		.then((response) => response.data);
};

export const fetchSections = async (courseId: number) => {
	return axios
		.get(urls.sections, {
			params: {
				parent: courseId,
			},
		})
		.then((response) => response.data);
};

export const fetchLessons = async (courseId: number) => {
	return axios
		.get(urls.lessons, {
			params: {
				parent: courseId,
			},
		})
		.then((response) => response.data);
};

export const deleteCourse = async (courseId: any) => {
	return axios
		.delete(urls.course.replace(':id', courseId.toString()))
		.then((response) => response.data);
};

export const addCourse = async (data: any) => {
	return axios.post(urls.courses, data).then((response) => response.data);
};

export const updateCourse = async (courseId: any, data: any) => {
	return axios
		.patch(urls.course.replace(':id', courseId.toString()), data)
		.then((response) => response.data);
};
