import { Box, useBreakpointValue } from '@chakra-ui/react';
import React from 'react';

const DesktopHidden: React.FC = (props) => {
	const { children } = props;
	const display = useBreakpointValue({
		base: 'block',
		md: 'none',
	});
	return <Box d={display}>{children}</Box>;
};

export default DesktopHidden;
