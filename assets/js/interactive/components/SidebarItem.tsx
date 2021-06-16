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
import { BiMinus, BiPlay, BiPlus, BiTimer } from 'react-icons/bi';
import { Link } from 'react-router-dom';
import routes from '../constants/routes';

interface Props {
	id: number;
	name: string;
	contents: any;
	contentsMap: any;
}

const SidebarItem: React.FC<Props> = (props) => {
	const { id, name, contents, contentsMap } = props;
	const newContents = contents?.map((contentId: any) => contentsMap[contentId]);

	const centerStyles = {
		width: '23px',
		height: '23px',
		bg: 'white',
		rounded: 'full',
	};

	return (
		<AccordionItem isDisabled={!newContents.length} _first={{ borderTop: 0 }}>
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
					{newContents && (
						<AccordionPanel p="0" bg="#F8FAFF">
							<List>
								{newContents.map(
									(
										content: { id: number; name: string; type: string },
										index: number
									) => (
										<ListItem
											key={index}
											borderTop="1px"
											borderTopColor="gray.200"
											px="3"
											py="3">
											<Link
												to={routes.lesson.replace(
													':lessonId',
													content.id.toString()
												)}>
												<Stack direction="row" spacing="2" alignItems="center">
													<Icon
														as={content.type === 'quiz' ? BiTimer : BiPlay}
														color="blue.500"
														fontSize="xl"
													/>
													<Text
														color="gray.300"
														fontSize="xs"
														fontWeight="bold">
														{index + 1}
													</Text>
													<Text fontSize="xs">{content.name}</Text>
												</Stack>
											</Link>
										</ListItem>
									)
								)}
							</List>
						</AccordionPanel>
					)}
				</>
			)}
		</AccordionItem>
	);
};

export default SidebarItem;
