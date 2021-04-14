import {
	Box,
	Button,
	ButtonGroup,
	Divider,
	Flex,
	Heading,
	IconButton,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import FormGroup from 'Components/common/FormGroup';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import Slider from 'rc-slider';
import React, { useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiDotsVerticalRounded, BiEdit, BiTrash } from 'react-icons/bi';
import { useMutation, useQuery } from 'react-query';
import { useHistory, useParams } from 'react-router';
import { useToasts } from 'react-toast-notifications';

import Description from './components/Description';
import FeaturedImage from './components/FeaturedImage';
import Name from './components/Name';

const AddNewLesson: React.FC = () => {
	const { sectionId }: any = useParams();
	const methods = useForm();
	const { addToast } = useToasts();
	const { push } = useHistory();

	const sectionQuery = useQuery([`section${sectionId}`, sectionId], () =>
		fetchSection(sectionId)
	);

	const courseId = sectionQuery?.data?.parent_id;

	const addLessonMutation = useMutation(
		(data: object) =>
			addLesson({
				...data,
				parent_id: sectionId,
				course_id: courseId,
			}),
		{
			onSuccess: (data: any) => {
				addToast(data?.name + __(' has been added successfully'), {
					appearance: 'success',
					autoDismiss: true,
				});
				push(`/builder/${courseId}`);
			},
		}
	);
	const onSubmit = (data: object) => {
		addLessonMutation.mutate(data);
	};

	return (
		<FormProvider {...methods}>
			<Box bg="white" p="10" shadow="box">
				<Stack direction="column" spacing="8">
					<Flex aling="center" justify="space-between">
						<Heading as="h1" fontSize="x-large">
							{__('Add New Lesson', 'masteriyo')}
						</Heading>
						<Menu placement="bottom-end">
							<MenuButton
								as={IconButton}
								icon={<BiDotsVerticalRounded />}
								variant="outline"
								rounded="sm"
								fontSize="large"
							/>
							<MenuList>
								<MenuItem icon={<BiEdit />}>{__('Edit', 'masteriyo')}</MenuItem>
								<MenuItem icon={<BiTrash />}>
									{__('Delete', 'masteriyo')}
								</MenuItem>
							</MenuList>
						</Menu>
					</Flex>

					<form onSubmit={methods.handleSubmit(onSubmit)}>
						<Stack direction="column" spacing="6">
							<Name />
							<Description />
							<FeaturedImage />

							<Box py="3">
								<Divider />
							</Box>

							<ButtonGroup>
								<Button colorScheme="blue" type="submit">
									{__('Add New Lesson', 'masteriyo')}
								</Button>
								<Button
									variant="outline"
									onClick={() => push(`/builder/${courseId}`)}>
									{__('Cancel', 'masteriyo')}
								</Button>
							</ButtonGroup>
						</Stack>
					</form>
				</Stack>
			</Box>
		</FormProvider>
	);
};

export default AddNewLesson;
