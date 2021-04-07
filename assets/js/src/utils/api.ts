import http from './http';

class API {
	uri: string;

	constructor(uri: string) {
		this.uri = uri;
	}

	list(query: any) {
		return http({
			url: this.uri,
			method: 'get',
			params: query,
		});
	}

	get(id: number) {
		return http({
			url: `${this.uri}/${id}`,
			method: 'get',
		});
	}

	store(data: any) {
		return http({
			url: this.uri,
			method: 'post',
			data: data,
		});
	}

	update(id: number, data: any) {
		return http({
			url: `${this.uri}/${id}`,
			method: 'patch',
			data: data,
		});
	}

	delete(id: number) {
		return http({
			url: `${this.uri}/${id}`,
			method: 'delete',
		});
	}
}

export default API;
