export const isDevelopment =
	process.env.NODE_ENV === 'development' ? true : false;

export const isProduction =
	process.env.NODE_ENV === 'production' ? true : false;

export const hasNumber = /^\D+$/i;

export const getYoutubeId = (url: string) => {
	var regExp =
		/^https?\:\/\/(?:www\.youtube(?:\-nocookie)?\.com\/|m\.youtube\.com\/|youtube\.com\/)?(?:ytscreeningroom\?vi?=|youtu\.be\/|vi?\/|user\/.+\/u\/\w{1,2}\/|embed\/|watch\?(?:.*\&)?vi?=|\&vi?=|\?(?:.*\&)?vi?=)([^#\&\?\n\/<>"']*)/i;
	var match = url.match(regExp);
	return match && match[1].length == 11 ? match[1] : false;
};
