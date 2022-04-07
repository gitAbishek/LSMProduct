import {
	Button,
	ButtonGroup,
	FormControl,
	FormLabel,
	Icon,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useContext, useState } from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import { BiPlus } from 'react-icons/bi';
import { useQuery } from 'react-query';
import Select from 'react-select';
import { reactSelectStyles } from '../../../config/styles';
import urls from '../../../constants/urls';
import { CreateCatModal } from '../../../context/CreateCatProvider';
import { CourseCategoriesResponse } from '../../../types/course';
import API from '../../../utils/api';
import { makeCategoriesHierarchy } from '../../../utils/categories';

interface Props {
	defaultValue?: any;
}

interface CategoryOption {
	value: any;
	label: string;
}

const Categories: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const [categoriesList, setCategoriesList] = useState<CategoryOption[]>([]);
	const { setIsCreateCatModalOpen } = useContext(CreateCatModal);

	const categoryAPI = new API(urls.categories);

	const categoriesQuery = useQuery<CourseCategoriesResponse>(
		'categoryLists',
		() => categoryAPI.list({ per_page: 100 }),
		{
			retry: false,
			onSuccess: (data) => {
				const categories = makeCategoriesHierarchy(data?.data);

				setCategoriesList(
					categories.map((cat) => ({
						value: cat.id,
						label: '— '.repeat(cat.depth) + cat.name,
					}))
				);
			},
		}
	);
	const { control } = useFormContext();

	if (categoriesQuery.isSuccess) {
		return (
			<FormControl>
				<FormLabel>{__('Categories', 'masteriyo')}</FormLabel>
				<Controller
					name="categories"
					control={control}
					defaultValue={
						defaultValue &&
						defaultValue?.map((category: any) => {
							return {
								value: category.id,
								label: category.name,
							};
						})
					}
					render={({ field: { onChange, value } }) => (
						<Select
							onChange={onChange}
							value={value}
							styles={reactSelectStyles}
							closeMenuOnSelect={false}
							isMulti
							options={categoriesList}
						/>
					)}
				/>
				<ButtonGroup mt="4">
					<Button
						variant="link"
						leftIcon={<Icon fontSize="xl" as={BiPlus} />}
						onClick={() => setIsCreateCatModalOpen(true)}
						_hover={{ color: 'blue.500' }}>
						{__('Add New Category', 'masteriyo')}
					</Button>
				</ButtonGroup>
			</FormControl>
		);
	}

	return <></>;
};

export default Categories;
