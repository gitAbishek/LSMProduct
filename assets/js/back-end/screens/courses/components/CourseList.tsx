import {
	Avatar,
	Badge,
	Button,
	ButtonGroup,
	Icon,
	IconButton,
	Link,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import {
	BiCalendar,
	BiDotsVerticalRounded,
	BiEdit,
	BiShow,
	BiTrash,
	BiWrench,
} from 'react-icons/bi';
import { Link as RouterLink } from 'react-router-dom';
import { Td, Tr } from 'react-super-responsive-table';
import PriceWithSymbol from '../../../components/common/PriceWithSymbol';
import routes from '../../../constants/routes';
import { CourseCategorySchema } from '../../../schemas';
import { isEmpty } from '../../../utils/utils';
interface Props {
	id: number;
	name: string;
	price?: any;
	categories?: any;
	permalink: string;
	editPostLink: string;
	createdOn: string;
	author: { id: number; display_name: string; avatar_url: string };
	onDeletePress: (id: number) => void;
	onTrashPress: (id: number) => void;
	onRestorePress: (id: number) => void;
	status?: 'draft' | 'publish' | 'trash';
}

const CourseList: React.FC<Props> = (props) => {
	const {
		id,
		name,
		price,
		categories,
		permalink,
		editPostLink,
		createdOn,
		author,
		onDeletePress,
		onTrashPress,
		onRestorePress,
		status,
	} = props;

	const createdOnDate = createdOn.split(' ')[0];
	//@ts-ignore
	const currencySymbol = window._MASTERIYO_.currency.symbol;

	return (
		<Tr>
			<Td>
				{status === 'trash' ? (
					<Text fontWeight="semibold">{name}</Text>
				) : (
					<Link
						as={RouterLink}
						to={routes.courses.edit.replace(':courseId', id.toString())}
						fontWeight="semibold"
						_hover={{ color: 'blue.500' }}>
						{name}
						{status === 'draft' ? (
							<Badge bg="blue.200" fontSize="10px" ml="2" mt="-2">
								{__('Draft', 'masteriyo')}
							</Badge>
						) : null}
					</Link>
				)}
			</Td>
			<Td>
				{!isEmpty(categories) ? (
					categories?.map((category: CourseCategorySchema) => (
						<Text
							as="span"
							fontSize="xs"
							fontWeight="medium"
							color="gray.600"
							key={category?.id}
							_last={{
								_after: {
									content: 'none',
								},
							}}
							_after={{
								content: `", "`,
							}}>
							{category?.name}
						</Text>
					))
				) : (
					<Text as="span" fontSize="xs" fontWeight="medium" color="gray.600">
						{__('Uncategorized', 'masteriyo')}
					</Text>
				)}
			</Td>
			<Td>
				<Stack direction="row" spacing="2" alignItems="center">
					<Avatar src={author?.avatar_url} size="xs" />
					<Text fontSize="xs" fontWeight="medium" color="gray.600">
						{author?.display_name}
					</Text>
				</Stack>
			</Td>
			<Td>
				{price === undefined || price < 1 ? (
					<Badge textTransform="none">{__('Free', 'masteriyo')}</Badge>
				) : (
					<Text fontWeight="medium" fontSize="xs">
						{PriceWithSymbol(price, currencySymbol)}
					</Text>
				)}
			</Td>
			<Td>
				<Stack direction="row" spacing="2" alignItems="center" color="gray.600">
					<Icon as={BiCalendar} />
					<Text fontSize="xs" fontWeight="medium">
						{createdOnDate}
					</Text>
				</Stack>
			</Td>
			<Td>
				{status === 'trash' ? (
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
							<MenuItem
								onClick={() => onRestorePress(id)}
								icon={<BiShow />}
								_hover={{ color: 'blue.500' }}>
								{__('Restore', 'masteriyo')}
							</MenuItem>
							<MenuItem
								onClick={() => onDeletePress(id)}
								icon={<BiTrash />}
								_hover={{ color: 'red.500' }}>
								{__('Delete Permanently', 'masteriyo')}
							</MenuItem>
						</MenuList>
					</Menu>
				) : (
					<ButtonGroup>
						<RouterLink
							to={routes.courses.edit.replace(':courseId', id.toString())}>
							<Button leftIcon={<BiEdit />} colorScheme="blue" size="xs">
								{__('Edit', 'masteriyo')}
							</Button>
						</RouterLink>
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
								<Link href={permalink} isExternal>
									<MenuItem icon={<BiShow />}>
										{__('Preview', 'masteriyo')}
									</MenuItem>
								</Link>
								<Link href={editPostLink} isExternal>
									<MenuItem icon={<BiWrench />}>
										{__('WordPress Editor', 'masteriyo')}
									</MenuItem>
								</Link>
								<MenuItem
									onClick={() => onTrashPress(id)}
									icon={<BiTrash />}
									_hover={{ color: 'red.500' }}>
									{__('Trash', 'masteriyo')}
								</MenuItem>
							</MenuList>
						</Menu>
					</ButtonGroup>
				)}
			</Td>
		</Tr>
	);
};

export default CourseList;
