export default function timeAgo(input: string) {
	const date = new Date(input);
	const formatter = new Intl.RelativeTimeFormat('en');

	const years = 3600 * 24 * 365;
	const months = 3600 * 24 * 30;
	const weeks = 3600 * 24 * 7;
	const days = 3600 * 24;
	const hours = 3600;
	const minutes = 60;
	const seconds = 1;

	const secondsElapsed = (date.getTime() - Date.now()) / 1000;
	if (years < Math.abs(secondsElapsed)) {
		const delta = secondsElapsed / years;
		return formatter.format(Math.round(delta), 'years');
	} else if (months < Math.abs(secondsElapsed)) {
		const delta = secondsElapsed / months;
		return formatter.format(Math.round(delta), 'months');
	}
	if (weeks < Math.abs(secondsElapsed)) {
		const delta = secondsElapsed / weeks;
		return formatter.format(Math.round(delta), 'weeks');
	}
	if (days < Math.abs(secondsElapsed)) {
		const delta = secondsElapsed / days;
		return formatter.format(Math.round(delta), 'days');
	}
	if (hours < Math.abs(secondsElapsed)) {
		const delta = secondsElapsed / hours;
		return formatter.format(Math.round(delta), 'hours');
	}
	if (minutes < Math.abs(secondsElapsed)) {
		const delta = secondsElapsed / minutes;
		return formatter.format(Math.round(delta), 'minutes');
	} else {
		const delta = secondsElapsed / seconds;
		return formatter.format(Math.round(delta), 'seconds');
	}
}
