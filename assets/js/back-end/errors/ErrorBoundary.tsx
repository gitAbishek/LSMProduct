import React, { Component, ErrorInfo, PropsWithChildren } from 'react';

class ErrorBoundary extends Component<PropsWithChildren<any>, any> {
	constructor(props: PropsWithChildren<any>) {
		super(props);
		this.state = { hasError: false };
	}

	static getDerivedStateFromError() {
		return { hasError: true };
	}

	componentDidCatch(error: Error, errorInfo: ErrorInfo) {
		console.error('Uncaught error:', error, errorInfo);
	}

	render() {
		if (this.state.hasError) {
			return <h1>Sorry.. there was an error</h1>;
		}

		return this.props.children;
	}
}

export default ErrorBoundary;
