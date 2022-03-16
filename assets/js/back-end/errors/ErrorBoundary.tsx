import { Box, Center, Code, Icon, Stack } from '@chakra-ui/react';
import React, { Component, ErrorInfo, PropsWithChildren } from 'react';
import { Five0Five } from '../constants/images';

class ErrorBoundary extends Component<PropsWithChildren<any>, any> {
	constructor(props: PropsWithChildren<any>) {
		super(props);
		this.state = { error: null, errorInfo: null };
	}

	public componentDidCatch(error: Error, errorInfo: ErrorInfo) {
		this.setState({
			error: error,
			errorInfo: errorInfo,
		});
	}

	render() {
		if (this.state.error) {
			return (
				<Center minH="90vh">
					<Box textAlign="center">
						<Icon as={Five0Five} w="300px" h="180px" />
						<Stack direction="column" align="center">
							<Code>{this.state.error.toString()}</Code>
							<Code maxW="3xl" colorScheme="red" p="8">
								{this.state.errorInfo.componentStack}
							</Code>
						</Stack>
					</Box>
				</Center>
			);
		}

		return this.props.children;
	}
}

export default ErrorBoundary;
