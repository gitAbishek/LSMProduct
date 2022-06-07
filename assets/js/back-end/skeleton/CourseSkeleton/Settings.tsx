import {
	Box,
	List,
	ListItem,
	Skeleton,
	SkeletonText,
	Stack,
} from '@chakra-ui/react';
import React from 'react';

const Settings = () => (
	<Box
		bg="white"
		p={['4', null, '10']}
		shadow="box"
		d="flex"
		gap="10px"
		flexDirection="row">
		<Stack direction="column" spacing="6" flex="1">
			<List
				borderRight="1px"
				borderRightColor="gray.100"
				mr="6"
				spacing="6"
				h="100%">
				<ListItem>
					<SkeletonText noOfLines={1} width="52px" />
				</ListItem>
				<ListItem>
					<SkeletonText noOfLines={1} width="52px" />
				</ListItem>{' '}
				<ListItem>
					<SkeletonText noOfLines={1} width="52px" />
				</ListItem>
			</List>
		</Stack>
		<Stack direction="column" spacing="10" flex="6">
			{[1, 2, 3].map((x) => (
				<Stack key={x} direction="column" spacing="3">
					<SkeletonText noOfLines={1} width="44px" />
					<Skeleton height="40px" />
				</Stack>
			))}
		</Stack>
	</Box>
);

export default Settings;
