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
import dayjs from 'dayjs';
import React from 'react';
import {
	BiCalendar,
	BiDetail,
	BiDotsVerticalRounded,
	BiEdit,
	BiShow,
	BiTrash,
} from 'react-icons/bi';
import { CgUnblock } from 'react-icons/cg';
import { IoMdStar, IoMdStarOutline } from 'react-icons/io';
import { RiSpam2Fill } from 'react-icons/ri';
import { Link as RouterLink, useHistory } from 'react-router-dom';
import { Td, Tr } from 'react-super-responsive-table';
import routes from '../../../constants/routes';
import { ReviewStatus } from '../../../enums/Enum';

interface Props {
	id: number;
	title: string;
	author: { id: number; display_name: string; avatar_url: string };
	course: string;
	rating: number;
	status: string;
	createdAt: string;
	replies_count: number;
	onDeletePress: () => void;
	onTrashPress: () => void;
	onRestorePress: () => void;
	onSpamPress: () => void;
	onUnSpamPress: () => void;
}

const ReviewList: React.FC<Props> = (props) => {
	const {
		id,
		title,
		status,
		author,
		course,
		rating,
		replies_count,
		createdAt,
		onDeletePress,
		onTrashPress,
		onRestorePress,
		onSpamPress,
		onUnSpamPress,
	} = props;

	const history = useHistory();

	const reviewStatus =
		status === ReviewStatus.approve
			? { color: 'green', text: 'Approved' }
			: status === ReviewStatus.hold
			? { color: 'orange', text: 'On Hold' }
			: status === ReviewStatus.spam
			? { color: 'pink', text: 'Spam' }
			: { color: 'red', text: 'Trash' };

	const renderRatings = (rate: number) => {
		const average = Number(rate);
		let stars = [];

		for (let i = 1; i <= 5; i++) {
			if (i <= average) {
				stars.push(<Icon key={i} as={IoMdStar} />);
			} else {
				stars.push(<Icon key={i} as={IoMdStarOutline} />);
			}
		}

		return (
			<Stack direction="row" spacing="0" color="orange.300">
				{stars}
			</Stack>
		);
	};

	const editReviewLink = routes.reviews.edit.replace(
		':reviewId',
		id.toString()
	);

	return (
		<Tr>
			<Td>
				{status === ReviewStatus.trash ? (
					<Text fontWeight="semibold">{title}</Text>
				) : (
					<Link
						as={RouterLink}
						to={editReviewLink}
						fontWeight="semibold"
						_hover={{ color: 'blue.500' }}>
						{title}
					</Link>
				)}
				{renderRatings(rating)}
			</Td>
			<Td>
				<Text fontSize="xs" fontWeight="medium" color="gray.600">
					{course}
				</Text>
			</Td>
			<Td>
				<Badge colorScheme={reviewStatus.color}>
					{reviewStatus.text.toUpperCase()}
				</Badge>
			</Td>
			<Td>
				<Stack direction="row" spacing="2" alignItems="center">
					<Avatar size="xs" src={author.avatar_url} />
					<Text fontSize="xs" fontWeight="medium" color="gray.600">
						{author?.display_name}
					</Text>
				</Stack>
			</Td>
			<Td>
				<RouterLink to={{ pathname: editReviewLink, search: '?page=replies' }}>
					{replies_count}
				</RouterLink>
			</Td>
			<Td>
				<Stack direction="row" spacing="2" alignItems="center" color="gray.600">
					<Icon as={BiCalendar} />
					<Text fontSize="xs" fontWeight="medium">
						{dayjs(createdAt).format('MM/DD/YYYY, hh:mm:ss A')}
					</Text>
				</Stack>
			</Td>
			<Td>
				{status === ReviewStatus.trash ? (
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
							<MenuItem onClick={onRestorePress} icon={<BiShow />} _>
								{__('Restore', 'masteriyo')}
							</MenuItem>
							<MenuItem onClick={onDeletePress} icon={<BiTrash />}>
								{__('Delete Permanently', 'masteriyo')}
							</MenuItem>
						</MenuList>
					</Menu>
				) : (
					<ButtonGroup>
						<RouterLink
							to={routes.reviews.edit.replace(':reviewId', id.toString())}>
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
								<MenuItem
									onClick={() =>
										history.push({
											pathname: editReviewLink,
											search: '?page=replies',
										})
									}
									icon={<BiDetail />}>
									{__('View Replies', 'masteriyo')}
								</MenuItem>
								{status !== ReviewStatus.spam ? (
									<MenuItem onClick={onSpamPress} icon={<RiSpam2Fill />}>
										{__('Mark Spam', 'masteriyo')}
									</MenuItem>
								) : (
									<MenuItem onClick={onUnSpamPress} icon={<CgUnblock />}>
										{__('Mark Unspam', 'masteriyo')}
									</MenuItem>
								)}
								<MenuItem
									onClick={onTrashPress}
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

export default ReviewList;
