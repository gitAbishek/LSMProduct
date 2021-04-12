import {
	Box,
	Collapse,
	Flex,
	IconButton,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Spinner,
	Stack,
	Text,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import AddNewButton from 'Components/common/AddNewButton';
import React, { useState } from 'react';
import {
	BiAlignLeft,
	BiDotsVerticalRounded,
	BiEdit,
	BiTimer,
	BiTrash,
} from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { NavLink, useHistory } from 'react-router-dom';

import { Edit } from '../../../assets/icons';
import routes from '../../../constants/routes';
import urls from '../../../constants/urls';
import API from '../../../utils/api';
import DragHandle from '../components/DragHandle';
import Content from './Content';
import EditSection from './EditSection';

interface Props {
	id: number;
	name: string;
	courseId: number;
	description?: any;
}

const Section: React.FC<Props> = (props) => {
	const { id, name, description } = props;
	const [isEditing, setIsEditing] = useState(false);
	const [isModalOpen, setIsModalOpen] = useState(false);
	const contentAPI = new API(urls.contents);
	const sectionAPI = new API(urls.sections);
	const history = useHistory();

	const queryClient = useQueryClient();
	const toast = useToast();

	const contentQuery = useQuery(['contents', id], () =>
		contentAPI.list({ section: id })
	);

	const deleteMutation = useMutation((id: number) => sectionAPI.delete(id), {
		onSuccess: (data: any) => {
			toast({
				title: data?.name + __(' has been deleted successfully'),
				description: __(' has been deleted successfully'),
				isClosable: true,
				status: 'error',
			});
			queryClient.invalidateQueries('builderSections');
		},
	});

	const onDeletePress = () => {
		setIsModalOpen(true);
	};

	const onModalClose = () => {
		setIsModalOpen(false);
	};

	const onDeleteConfirm = () => {
		deleteMutation.mutate(id);
	};

	const onAddNewLessonPress = () => {
		history.push(routes.lesson.add.replace(':sectionId', id.toString()));
	};
	const onAddNewQuizPress = () => {
		history.push(routes.quiz.add.replace(':sectionId', id.toString()));
	};
	return (
		<Box bg="white" p="12" shadow="box">
			<Flex justify="space-between" align="center">
				<Stack direction="row" spacing="3">
					<DragHandle />
					<Text>{name}</Text>
				</Stack>
				<Menu>
					<MenuButton
						as={IconButton}
						icon={<BiDotsVerticalRounded />}
						variant="outline"
					/>
					<MenuList>
						<MenuItem icon={<BiEdit />}>{__('Edit', 'masteriyo')}</MenuItem>
						<MenuItem icon={<BiTrash />}>{__('Delete', 'masteriyo')}</MenuItem>
					</MenuList>
				</Menu>
			</Flex>
			<Box>
				<Collapse in={isEditing} animateOpacity>
					{/* <EditSection
						id={id}
						name={name}
						description={description}
						onSave={() => setIsEditing(false)}
						onCancel={() => setIsEditing(false)}
					/> */}
				</Collapse>
				{contentQuery.isLoading ? (
					<Spinner />
				) : (
					contentQuery?.data?.map((content: any) => (
						<Content
							key={content.id}
							id={content.id}
							name={content.name}
							type={content.type}
						/>
					))
				)}
			</Box>
			<Box>
				<Menu>
					<MenuButton as={AddNewButton}>
						{__('Add New Content', 'masteriyo')}
					</MenuButton>
					<MenuList>
						<MenuItem icon={<BiAlignLeft />} onClick={onAddNewLessonPress}>
							{__('Add New Lesson', 'masteriyo')}
						</MenuItem>
						<MenuItem icon={<BiAlignLeft />} onClick={onAddNewQuizPress}>
							{__('Add New Quiz', 'masteriyo')}
						</MenuItem>
					</MenuList>
				</Menu>
			</Box>
		</Box>
	);
};

export default Section;
