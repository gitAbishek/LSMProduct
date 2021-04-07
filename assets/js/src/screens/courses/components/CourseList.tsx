import {
	Badge,
	ButtonGroup,
	IconButton,
	Link,
	Stack,
	Td,
	Tooltip,
	Tr,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import DeleteModal from 'Components/layout/DeleteModal';
import React, { useState } from 'react';
import { BiEdit, BiTrash } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import { Link as RouterLink, useHistory } from 'react-router-dom';

import routes from '../../../constants/routes';
import { deleteCourse } from '../../../utils/api';

interface Props {
	id: number;
	name: string;
	price?: any;
	categories?: any;
}

const CourseList: React.FC<Props> = (props) => {
	const { id, name, price, categories } = props;
	const history = useHistory();
	const queryClient = useQueryClient();
	const [isModalOpen, setIsModalOpen] = useState(false);

	const deleteMutation = useMutation((id: number) => deleteCourse(id), {
		onSuccess: () => {
			queryClient.invalidateQueries('courseList');
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

	const onEditPress = () => {
		history.push(routes.courses.edit.replace(':courseId', id.toString()));
	};

	return (
		<>
			<Tr key={id}>
				<Td>
					<Link
						as={RouterLink}
						to={`/builder/${id}`}
						fontWeight="semibold"
						_hover={{ color: 'blue.500' }}>
						{name}
					</Link>
				</Td>
				<Td>
					<Stack direction="row">
						{categories.map((category: any) => (
							<Badge>{category.name}</Badge>
						))}
					</Stack>
				</Td>
				<Td>{price}</Td>
				<Td>
					<ButtonGroup>
						<Tooltip label={__('Edit Course', 'masteriyo')}>
							<IconButton
								icon={<BiEdit />}
								colorScheme="blue"
								variant="link"
								size="lg"
								aria-label={__('Edit Course', 'masteriyo')}
								onClick={() => onEditPress()}
							/>
						</Tooltip>
						<Tooltip label={__('Delete Course', 'masteriyo')}>
							<IconButton
								icon={<BiTrash />}
								colorScheme="red"
								variant="link"
								size="lg"
								aria-label={__('Delete Course', 'masteriyo')}
								onClick={() => onDeletePress()}
							/>
						</Tooltip>
					</ButtonGroup>
				</Td>
			</Tr>
			<DeleteModal
				isOpen={isModalOpen}
				onClose={onModalClose}
				onDeletePress={onDeleteConfirm}
				title={name}
			/>
		</>
	);
};

export default CourseList;
