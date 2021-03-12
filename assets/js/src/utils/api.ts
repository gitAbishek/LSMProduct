import { isDevelopment, isProduction } from './helper';

import axios from 'axios';
import urls from '../constants/urls';

axios.interceptors.request.use(function (config) {
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

export const fetchCourses = () => {
	return axios.get(urls.courses).then((response) => response.data);
};

export const fetchCourse = (id: any) => {
	return axios
		.get(urls.course.replace(':id', id))
		.then((response) => response.data);
};

export const fetchSections = (courseId: number) => {
	return axios
		.get(urls.sections, {
			params: {
				parent: courseId,
			},
		})
		.then((response) => response.data);
};

export const fetchSection = (id: number) => {
	return axios
		.get(urls.section.replace(':id', id.toString()))
		.then((response) => response.data);
};

export const fetchContents = (sectionId: number) => {
	return axios
		.get(urls.contents, { params: { section: sectionId } })
		.then((response) => response.data);
};

export const deleteCourse = (courseId: any) => {
	return axios
		.delete(urls.course.replace(':id', courseId.toString()))
		.then((response) => response.data);
};

export const addCourse = (data: any) => {
	return axios.post(urls.courses, data).then((response) => response.data);
};

export const updateCourse = (courseId: any, data: any) => {
	return axios
		.patch(urls.course.replace(':id', courseId.toString()), data)
		.then((response) => response.data);
};

export const addSection = (data: any) => {
	return axios.post(urls.sections, data).then((response) => response.data);
};

export const updateSection = (id: number, data: any) => {
	return axios
		.patch(urls.section.replace(':id', id.toString()), data)
		.then((response) => response.data);
};

export const deleteSection = (id: number) => {
	return axios
		.delete(urls.section.replace(':id', id.toString()))
		.then((response) => response.data);
};

export const fetchLessons = (courseId: number) => {
	return axios
		.get(urls.lessons, {
			params: {
				parent: courseId,
			},
		})
		.then((response) => response.data);
};

export const fetchLesson = (lessonId: number) => {
	return axios
		.get(urls.lesson.replace(':id', lessonId.toString()))
		.then((response) => response.data);
};

export const addLesson = (data: any) => {
	return axios.post(urls.lessons, data).then((response) => response.data);
};

export const updateLesson = (id: number, data: any) => {
	return axios
		.patch(urls.lesson.replace(':id', id.toString()), data)
		.then((response) => response.data);
};

export const deleteLesson = (id: number) => {
	return axios
		.delete(urls.lesson.replace(':id', id.toString()))
		.then((response) => response.data);
};

export const addQuiz = (data: any) => {
	return axios.post(urls.quizes, data).then((response) => response.data);
};

export const deleteQuiz = (id: number) => {
	return axios
		.delete(urls.quiz.replace(':id', id.toString()))
		.then((response) => response.data);
};
