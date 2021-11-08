import {
	Box,
	Collapse,
	Flex,
	IconButton,
	Input,
	Stack,
	useMediaQuery,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { BiDotsVerticalRounded } from 'react-icons/bi';
import { useOnType } from 'use-ontype';
import { deepClean } from '../../../utils/utils';

interface FilterParams {
	search?: string;
}

interface Props {
	filterParams: FilterParams;
	setFilterParams: any;
}

const CategoriesFilter: React.FC<Props> = (props) => {
	const { setFilterParams, filterParams } = props;
	const { handleSubmit } = useForm();
	const [isMobile] = useMediaQuery('(min-width: 48em)');

	const onSearchInput = useOnType(
		{
			onTypeFinish: (val: string) => {
				setFilterParams({
					search: val,
				});
			},
		},
		800
	);
	const [isOpen, setIsOpen] = useState(isMobile);

	const onChange = (data: FilterParams) => {
		setFilterParams(deepClean(data));
	};

	useEffect(() => {
		setIsOpen(isMobile);
	}, [isMobile]);

	return (
		<Box px={{ base: 6, md: 12 }}>
			<Flex justify="end">
				{!isMobile && (
					<IconButton
						icon={<BiDotsVerticalRounded />}
						variant="outline"
						rounded="sm"
						fontSize="large"
						aria-label={__('toggle filter', 'masteriyo')}
						onClick={() => setIsOpen(!isOpen)}
					/>
				)}
			</Flex>
			<Collapse in={isOpen}>
				<form onChange={handleSubmit(onChange)}>
					<Stack
						direction={['column', null, 'row']}
						spacing="4"
						mt={[6, null, 0]}>
						<Input
							defaultValue={filterParams?.search}
							placeholder={__('Search categories', 'masteriyo')}
							{...onSearchInput}
						/>
					</Stack>
				</form>
			</Collapse>
		</Box>
	);
};

export default CategoriesFilter;
