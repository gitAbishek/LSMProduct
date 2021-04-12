import {
	Box,
	Flex,
	Icon,
	IconButton,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Button from 'Components/common/Button';
import Dropdown from 'Components/common/Dropdown';
import DropdownOverlay from 'Components/common/DropdownOverlay';
import OptionButton from 'Components/common/OptionButton';
import {
	Card,
	CardActions,
	CardHeader,
	CardHeading,
} from 'Components/layout/Card';
import DeleteModal from 'Components/layout/DeleteModal';
import React, { useState } from 'react';
import {
	BiAlignLeft,
	BiDotsVerticalRounded,
	BiEdit,
	BiTimer,
	BiTrash,
} from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import { useHistory } from 'react-router';

import { AlignLeft, Sortable, Timer, Trash } from '../../../assets/icons';
import DragHandle from './DragHandle';

interface Props {
	id: number;
	name: string;
	type: string;
}

const Content: React.FC<Props> = (props) => {
	const { id, name, type } = props;
	const [isModalOpen, setIsModalOpen] = useState(false);
	const queryClient = useQueryClient();
	const { push } = useHistory();

	const deleteLessonMutation = useMutation((id: number) => deleteLesson(id), {
		onSuccess: () => {
			queryClient.invalidateQueries('contents');
		},
	});
	const deleteQuizMutation = useMutation((id: number) => deleteQuiz(id), {
		onSuccess: () => {
			queryClient.invalidateQueries('contents');
		},
	});

	const onModalClose = () => {
		setIsModalOpen(false);
	};

	const onDeletePress = () => {
		setIsModalOpen(true);
	};

	const deleteContent = () => {
		if (type === 'lesson') {
			deleteLessonMutation.mutate(id);
		} else if (type === 'quiz') {
			deleteQuizMutation.mutate(id);
		}
		setIsModalOpen(false);
	};

	const onEditPress = () => {};

	return (
		<Box rounded="sm" border="1px" borderColor="gray.100" p="1">
			<Flex justify="space-between">
				<Stack direction="row" spacing="3" align="center" fontSize="xl">
					<Icon as={Sortable} />
					<Icon as={type === 'lesson' ? BiAlignLeft : BiTimer} />
					<Text fontSize="sm">{name}</Text>
				</Stack>
				<Stack direction="row" spacing="3">
					<Menu placement="bottom-end">
						<MenuButton
							as={IconButton}
							icon={<BiDotsVerticalRounded />}
							variant="outline"
							rounded="sm"
							size="sm"
							fontSize="large"
						/>
						<MenuList>
							<MenuItem onClick={onEditPress} icon={<BiEdit />}>
								{__('Edit', 'masteriyo')}
							</MenuItem>
							<MenuItem onClick={onDeletePress} icon={<BiTrash />}>
								{__('Delete', 'masteriyo')}
							</MenuItem>
						</MenuList>
					</Menu>
				</Stack>
			</Flex>
		</Box>
	);
};

export default Content;
