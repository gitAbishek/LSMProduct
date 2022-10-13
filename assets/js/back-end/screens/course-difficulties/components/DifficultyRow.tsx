import { Button, ButtonGroup, Link } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiEdit } from 'react-icons/bi';
import { Link as RouterLink } from 'react-router-dom';
import { Td, Tr } from 'react-super-responsive-table';
import routes from '../../../constants/routes';

interface Props {
	id: number;
	name: string;
	slug: string;
	count: Number;
}

const DifficultyRow: React.FC<Props> = (props) => {
	const { id, name, slug, count } = props;

	return (
		<Tr>
			<Td>
				<Link
					as={RouterLink}
					to={routes.course_difficulties.edit.replace(
						':difficultyId',
						id.toString()
					)}
					fontWeight="semibold"
					_hover={{ color: 'primary.500' }}>
					{name}
				</Link>
			</Td>
			<Td>{slug}</Td>
			<Td>{count}</Td>
			<Td>
				<ButtonGroup>
					<RouterLink
						to={routes.course_difficulties.edit.replace(
							':difficultyId',
							id.toString()
						)}>
						<Button colorScheme="primary" leftIcon={<BiEdit />} size="xs">
							{__('Edit', 'masteriyo')}
						</Button>
					</RouterLink>
				</ButtonGroup>
			</Td>
		</Tr>
	);
};

export default DifficultyRow;
