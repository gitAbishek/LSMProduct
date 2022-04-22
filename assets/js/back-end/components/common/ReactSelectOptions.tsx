import { SkeletonText, Stack } from '@chakra-ui/react';
import React from 'react';
import { components, MenuListComponentProps } from 'react-select';

const ReactSelectOptions: React.ComponentType<
	MenuListComponentProps<any, boolean, any>
> = (props) => {
	return (
		<components.MenuList {...props}>
			{props.children}
			{props.selectProps.isFetchingNextPage ? (
				<Stack p="4">
					<SkeletonText noOfLines={1} />
				</Stack>
			) : null}
		</components.MenuList>
	);
};

export default ReactSelectOptions;
