import { Center, FormControl, FormLabel, Spinner } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Select from 'Components/common/Select';
import React, { useState } from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import { useQuery } from 'react-query';

import urls from '../../../constants/urls';
import API from '../../../utils/api';

interface Props {
	defaultValue?: any;
	name?: string;
	label?: string;
}

const Categories: React.FC<Props> = (props) => {
	const { defaultValue, name = 'categories', label = 'Categories' } = props;
	const [categoriesList, setCategoriesList] = useState<any>(null);
	const categoryAPI = new API(urls.categories);
	const categoryQuery = useQuery('categoryLists', () => categoryAPI.list(), {
		retry: false,
		onSuccess: (data) => {
			setCategoriesList(
				data.map((category: any) => {
					return {
						value: category.id,
						label: category.name,
					};
				})
			);
		},
	});
	const { control } = useFormContext();

	return (
		<>
			{categoryQuery.isLoading ? (
				<Center h="12">
					<Spinner />
				</Center>
			) : (
				<FormControl>
					<FormLabel>{__(label, 'masteriyo')}</FormLabel>
					<Controller
						defaultValue={
							defaultValue &&
							defaultValue?.map((category: any) => {
								return {
									value: category.id,
									label: category.name,
								};
							})
						}
						render={({ field }) => (
							<Select
								{...field}
								closeMenuOnSelect={false}
								isMulti
								options={categoriesList}
							/>
						)}
						control={control}
						name={name}
					/>
				</FormControl>
			)}
		</>
	);
};

export default Categories;
