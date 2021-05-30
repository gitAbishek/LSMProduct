import {
	Stack,
	Box,
	Flex,
	Heading,
	FormControl,
	FormLabel,
	Select,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';
import { useQuery } from 'react-query';
import PagesAPI from '../../../utils/pages';

const PagesSettings = () => {
	const { register } = useFormContext();
	const pageAPI = new PagesAPI();
	const pagesQuery = useQuery('pages', () => pageAPI.list());

	const renderPagesOption = () => {
		return pagesQuery?.data?.map((page: any) => (
			<option value={page.id}>{page.title.rendered}</option>
		));
	};

	console.log(pagesQuery?.data);
	return (
		<Stack direction="column" spacing="8">
			<Box>
				<Stack direction="column" spacing="8">
					<Flex
						align="center"
						justify="space-between"
						borderBottom="1px"
						borderColor="gray.100"
						pb="3">
						<Heading fontSize="lg" fontWeight="semibold">
							{__('General', 'masteriyo')}
						</Heading>
					</Flex>
					<FormControl>
						<FormLabel minW="2xs">
							{__('Add to cart behavior', 'masteriyo')}
						</FormLabel>
						<Select {...register('pages.profile_page')}>
							{renderPagesOption()}
						</Select>
					</FormControl>
				</Stack>
			</Box>
		</Stack>
	);
};

export default PagesSettings;
