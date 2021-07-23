const pickBy = (object: any) => {
	const obj = {};
	for (const key in object) {
		if (object[key]) {
			obj[key] = object[key];
		}
	}
	return obj;
};

export default pickBy;
