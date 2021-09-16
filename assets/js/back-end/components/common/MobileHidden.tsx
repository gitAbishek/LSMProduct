import { Box, useBreakpointValue } from '@chakra-ui/react';
import React from 'react';

const MobileHidden: React.FC = (props) => {
	const { children } = props;
	const display = useBreakpointValue({ base: 'none', md: 'block' });
	return (
		<>
			<Box d={display}>{children}</Box>
		</>
	);
};

export default MobileHidden;
