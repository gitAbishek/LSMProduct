import {
	Box,
	Skeleton,
	SkeletonCircle,
	SkeletonText,
	Stack,
} from '@chakra-ui/react';
import React from 'react';

const ButtonIcons = (props: { align: string }) => (
	<Stack
		direction="row"
		spacing="4"
		justifyContent={props.align}
		alignItems="center">
		<SkeletonCircle size="10" />
		<SkeletonText noOfLines={1} width="60px" />
	</Stack>
);

const Builder = () => (
	<Stack direction="column" justify="center" w="100%" spacing="5">
		{[0, 1].map((x) => (
			<Box
				key={x}
				bg="white"
				p={['4', null, '10']}
				shadow="box"
				d="flex"
				gap="10px"
				flexDirection="column">
				<Skeleton height="30px" width="200px" />
				<Skeleton height="30px" />
				<ButtonIcons align="left" />
			</Box>
		))}
		<ButtonIcons align={'center'} />
	</Stack>
);

export default Builder;
