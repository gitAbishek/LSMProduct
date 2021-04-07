import http from './http';

export const listData = async (uri: string, params: any) => {
	const res = await http({
		url: uri,
		method: 'GET',
		params: params,
	});
	return res;
};

export const getData = async (uri: string) => {
	const res = await http({
		url: uri,
		method: 'GET',
	});
	return res;
};

export const storeData = async (uri: string, data: any) => {
	const res = await http({
		url: uri,
		method: 'POST',
		data: data,
	});
	return res;
};

export const updateData = async (uri: string, data: any) => {
	const res = await http({
		url: uri,
		method: 'PATCH',
		data: data,
	});
	return res;
};

export const deleteData = async(uri:string) {
	const res = await http({
		url: uri,
		method: 'DELETE'
	})
	return res;
}