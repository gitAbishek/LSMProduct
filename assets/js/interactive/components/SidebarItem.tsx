import {
	AccordionButton,
	AccordionItem,
	AccordionPanel,
	Box,
	Center,
	Icon,
	List,
	ListItem,
	Stack,
	Text,
} from '@chakra-ui/react';
import React from 'react';
import {
	BiAlignLeft,
	BiCheck,
	BiMinus,
	BiPlay,
	BiPlus,
	BiTimer,
} from 'react-icons/bi';
import { Link } from 'react-router-dom';
import { CourseContentMap } from '../schemas';
import { getNavigationRoute } from './FloatingNavigation';

interface Props {
	courseId: number;
	id: number;
	name: string;
	contents: any;
}

const SidebarItem: React.FC<Props> = (props) => {
	const { courseId, name, contents } = props;

	const centerStyles = {
		width: '23px',
		height: '23px',
		bg: 'white',
		rounded: 'full',
	};

	const getContentIcon = (itemType: 'quiz' | 'lesson', video: boolean) => {
		if (itemType === 'quiz') {
			return BiTimer;
		}

		if (itemType === 'lesson') {
			if (video) {
				return BiPlay;
			} else {
				return BiAlignLeft;
			}
		}
	};
	return (
		<AccordionItem isDisabled={!contents.length} _first={{ borderTop: 0 }}>
			{({ isExpanded }) => (
				<>
					<h2>
						<AccordionButton _expanded={{ bg: '#F8FAFF' }}>
							<Box flex="1" textAlign="left" fontWeight="medium" fontSize="sm">
								{name}
							</Box>
							{isExpanded ? (
								<Center sx={centerStyles} shadow="md">
									<Icon as={BiMinus} />
								</Center>
							) : (
								<Center sx={centerStyles}>
									<Icon as={BiPlus} />
								</Center>
							)}
						</AccordionButton>
					</h2>
					{contents && (
						<AccordionPanel p="0" bg="#F8FAFF">
							<List>
								{contents.map((content: CourseContentMap, index: number) => (
									<ListItem
										key={index}
										borderTop="1px"
										borderTopColor="gray.200"
										px="3"
										py="3">
										<Link
											to={getNavigationRoute(
												content.item_id,
												content.item_type,
												courseId
											)}>
											<Stack direction="row" spacing="2" alignItems="center">
												<Icon
													as={getContentIcon(content.item_type, content.video)}
													color="blue.500"
													fontSize="xl"
												/>
												<Text color="gray.300" fontSize="xs" fontWeight="bold">
													{index + 1}
												</Text>
												<Text fontSize="xs">{content.item_title}</Text>
												{content.completed && <Icon as={BiCheck} />}
											</Stack>
										</Link>
									</ListItem>
								))}
							</List>
						</AccordionPanel>
					)}
				</>
			)}
		</AccordionItem>
	);
};

export default SidebarItem;
