import http from './http';

class API {
	uri: string;

	constructor(uri: string) {
		this.uri = `${uri}/`;
	}

	list(query?: any) {
		return http({
			url: this.uri,
			method: 'get',
			params: query,
		}).then((res) => res.data);
	}

	get(id: number) {
		return http({
			url: `${this.uri}/${id}`,
			method: 'get',
		}).then((res) => res.data);
	}

	store(data: any) {
		return http({
			url: this.uri,
			method: 'post',
			data: data,
		}).then((res) => res.data);
	}

	update(id: number, data: any) {
		return http({
			url: `${this.uri}/${id}`,
			method: 'patch',
			data: data,
		}).then((res) => res.data);
	}

	delete(id: number) {
		return http({
			url: `${this.uri}/${id}`,
			method: 'delete',
		}).then((res) => res.data);
	}
}

export default API;
