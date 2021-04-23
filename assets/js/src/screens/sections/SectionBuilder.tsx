import { Center, Stack } from '@chakra-ui/layout';
import { Spinner } from '@chakra-ui/spinner';
import { __ } from '@wordpress/i18n';
import AddNewButton from 'Components/common/AddNewButton';
import React, { useState } from 'react';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import Section from './components/Section';

const SectionBuilder = () => {
	const { courseId }: any = useParams();
	const queryClient = useQueryClient();
	const history = useHistory();
	const courseAPI = new API(urls.courses);
	const sectionAPI = new API(urls.sections);
	const [totalSectionsLength, setTotalSectionsLength] = useState<number>(0);
	const courseQuery = useQuery(
		['builderCourse', courseId],
		() => courseAPI.get(courseId),
		{
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);

	const sectionQuery = useQuery(
		['builderSections', courseId],
		() =>
			sectionAPI.list({
				parent: courseId,
				order: 'asc',
				orderBy: 'menu_order',
			}),
		{
			onSuccess: (data) => {
				setTotalSectionsLength(data.length);
			},
		}
	);

	const addSection = useMutation((data: any) => sectionAPI.store(data), {
		onSuccess: () => {
			queryClient.invalidateQueries('builderSections');
		},
	});

	const onAddNewSectionPress = () => {
		addSection.mutate({
			parent_id: courseId,
			course_id: courseId,
			name: 'New Section',
			menu_order: totalSectionsLength + 1,
		});
	};

	return (
		<Stack direction="column" spacing="8">
			{(courseQuery.isLoading || sectionQuery.isLoading) && (
				<Center minH="xs">
					<Spinner />
				</Center>
			)}

			{sectionQuery.isSuccess &&
				sectionQuery.data.map((section: any) => (
					<Section
						key={section.id}
						id={section.id}
						name={section.name}
						description={section.description}
						courseId={courseId}
					/>
				))}
			{addSection.isLoading && (
				<Center minH="24">
					<Spinner />
				</Center>
			)}
			{courseQuery.isSuccess && sectionQuery.isSuccess && (
				<Center>
					<AddNewButton onClick={onAddNewSectionPress}>
						{__('Add New Section', 'masteriyo')}
					</AddNewButton>
				</Center>
			)}
		</Stack>
	);
};

export default SectionBuilder;
