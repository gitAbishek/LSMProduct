import {
	Box,
	Button,
	ButtonGroup,
	IconButton,
	Link,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiDotsVerticalRounded, BiEdit, BiShow, BiTrash } from 'react-icons/bi';
import { Link as RouterLink } from 'react-router-dom';
import { Td, Tr } from 'react-super-responsive-table';
import routes from '../../../constants/routes';

interface Props {
	id: number;
	name: string;
	slug: string;
	count: Number;
	link: string;
	onDeletePress: any;
	background?: React.CSSProperties['background'];
}

const CategoryRow: React.FC<Props> = (props) => {
	const { id, name, slug, count, link, onDeletePress, background } = props;
	const tdStyle: React.CSSProperties = { background };

	return (
		<Tr>
			<Td style={tdStyle}>
				<Link
					as={RouterLink}
					to={routes.course_categories.edit.replace(
						':categoryId',
						id.toString()
					)}
					fontWeight="semibold"
					_hover={{ color: 'primary.500' }}>
					{name}
				</Link>
			</Td>
			<Td style={tdStyle}>{slug}</Td>
			<Td style={tdStyle}>
				<Link href={link} isExternal>
					{count}
				</Link>
			</Td>
			<Td style={tdStyle}>
				<ButtonGroup>
					<RouterLink
						to={routes.course_categories.edit.replace(
							':categoryId',
							id.toString()
						)}>
						<Button leftIcon={<BiEdit />} size="xs">
							{__('Edit', 'masteriyo')}
						</Button>
					</RouterLink>
					<Box>
						<Menu placement="bottom-end">
							<MenuButton
								as={IconButton}
								icon={<BiDotsVerticalRounded />}
								variant="outline"
								rounded="sm"
								fontSize="large"
								size="xs"
							/>
							<MenuList>
								<Link href={link} isExternal>
									<MenuItem icon={<BiShow />}>
										{__('View Category', 'masteriyo')}
									</MenuItem>
								</Link>
								<MenuItem onClick={() => onDeletePress(id)} icon={<BiTrash />}>
									{__('Delete', 'masteriyo')}
								</MenuItem>
							</MenuList>
						</Menu>
					</Box>
				</ButtonGroup>
			</Td>
		</Tr>
	);
};

export default CategoryRow;
