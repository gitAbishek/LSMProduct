export const isDevelopment = () => {
	return process.env.NODE_ENV === 'development' ? true : false;
};

export const isProduction = () => {
	return process.env.NODE_ENV === 'production' ? true : false;
};
