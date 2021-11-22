import {
	Box,
	Collapse,
	Flex,
	IconButton,
	Input,
	Select,
	Stack,
	useMediaQuery,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { BiDotsVerticalRounded } from 'react-icons/bi';
import { useOnType } from 'use-ontype';
import { deepClean, deepMerge } from '../../../../utils/utils';

interface FilterParams {
	search?: string;
	approved?: boolean | string;
}

interface Props {
	setFilterParams: any;
	filterParams: FilterParams;
}

const InstructorsFilter: React.FC<Props> = (props) => {
	const { setFilterParams, filterParams } = props;
	const { handleSubmit, register } = useForm();
	const [isMobile] = useMediaQuery('(min-width: 48em)');

	const onSearchInput = useOnType(
		{
			onTypeFinish: (val: string) => {
				setFilterParams({
					search: val,
					approved: filterParams.approved,
				});
			},
		},
		800
	);
	const [isOpen, setIsOpen] = useState(isMobile);

	const onChange = (data: FilterParams) => {
		setFilterParams(
			deepClean(deepMerge(data, { search: filterParams.search }))
		);
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
						<Select {...register('approved')} w="44">
							<option value="">{__('All', 'masteriyo')}</option>
							<option value="true">{__('Approved', 'masteriyo')}</option>
							<option value="false">{__('Unapproved', 'masteriyo')}</option>
						</Select>
						<Input
							placeholder={__('Search by username or email', 'masteriyo')}
							{...onSearchInput}
						/>
					</Stack>
				</form>
			</Collapse>
		</Box>
	);
};

export default InstructorsFilter;
