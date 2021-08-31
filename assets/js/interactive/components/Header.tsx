import {
	Box,
	CircularProgress,
	CircularProgressLabel,
	Container,
	Flex,
	Heading,
	IconButton,
	Image,
	List,
	ListItem,
	Popover,
	PopoverArrow,
	PopoverContent,
	PopoverTrigger,
	Progress,
	Slide,
	Stack,
	Text,
	useDisclosure,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiChevronDown, BiInfoCircle } from 'react-icons/bi';
import { Polygon } from '../../back-end/constants/images';
import { CourseProgressSummaryMap } from '../schemas';
import AvatarMenu from './AvatarMenu';

interface Props {
	summary: CourseProgressSummaryMap;
	isOpen: boolean;
	onToggle: () => void;
}

// @ts-ignore
const logoData = window._MASTERIYO_.logo;
// @ts-ignore
const siteTitle = window._MASTERIYO_.siteTitle;

const Header: React.FC<Props> = (props) => {
	const { summary, isOpen, onToggle } = props;

	const {
		onOpen: onProgressOpen,
		onClose: onProgressClose,
		isOpen: isProgressOpen,
	} = useDisclosure();

	return (
		<Box as="header" h="84px">
			<Slide
				direction="top"
				in={isOpen}
				style={{ zIndex: 999 }}
				className="masteriyo-interactive-header">
				<Box position="relative" shadow="box" bg="white">
					<IconButton
						onClick={onToggle}
						icon={
							<BiChevronDown
								style={{ transform: isOpen ? 'rotate(180deg)' : 'none' }}
							/>
						}
						aria-label={__('Toggle Header', 'masteriyo')}
						variant="unstyled"
						color="gray.400"
						sx={{
							w: '40px',
							h: '26px',
							backgroundImage: `url(${Polygon})`,
							position: 'absolute',
							backgroundSize: '100%',
							backgroundRepeat: 'no-repeat',
							d: 'flex',
							alignItems: 'center',
							justifyContent: 'center',
							right: '80px',
							bottom: '-20px',
							fontSize: '2xl',
							minW: 'auto',
							lineHeight: '0',
						}}
					/>
					<Container maxW="container.xl">
						<Stack
							direction="row"
							spacing="12"
							align="center"
							py="4"
							justifyContent="space-between">
							<Box w="165px">
								{'' != logoData ? (
									<Image src={logoData[0]} height="36px" />
								) : (
									<Text textAlign="center">{siteTitle}</Text>
								)}
							</Box>

							<Stack
								direction="row"
								spacing="6"
								align="center"
								flex="1"
								justify="space-between">
								<Stack direction="column" spacing="2" flex="1">
									<Stack direction="row" justify="space-between" align="center">
										<Stack direction="row" alignItems="center">
											<Flex>
												<Heading fontSize="lg">
													{Math.round(
														(summary.total.completed /
															(summary.total.pending +
																summary.total.completed)) *
															100
													)}
												</Heading>
												<Text fontSize="xs">%</Text>
											</Flex>

											<Text
												fontSize="10px"
												textTransform="uppercase"
												color="gray.600"
												fontWeight="bold">
												{__('Complete', 'masteriyo')}
											</Text>
										</Stack>
										<Stack
											direction="row"
											fontSize="xs"
											fontWeight="medium"
											color="gray.600">
											<Text>
												{summary.total.pending}/
												{summary.total.completed + summary.total.pending}
												{__(' Completed', 'masteriyo')} |{' '}
											</Text>
											<Text>
												{summary.lesson.pending}{' '}
												{__('lessons left', 'masteriyo')} |{' '}
											</Text>
											<Text>
												{summary.quiz.pending} {__('quiz left', 'masteriyo')}
											</Text>
											<Popover
												isOpen={isProgressOpen}
												onClose={onProgressClose}
												onOpen={onProgressOpen}>
												<PopoverTrigger>
													<IconButton
														py="0"
														minW="0"
														variant="link"
														minH="auto"
														icon={<BiInfoCircle />}
														aria-label="open progress"
													/>
												</PopoverTrigger>
												<PopoverContent p="6" w="240px">
													<PopoverArrow />
													<List>
														<ListItem
															borderBottom="1px"
															borderColor="gray.100"
															pb="6">
															<Stack direction="row" spacing="2">
																<CircularProgress
																	value={Math.round(
																		(summary.lesson.completed /
																			(summary.lesson.pending +
																				summary.lesson.completed)) *
																			100
																	)}
																	size="54px"
																	capIsRound
																	trackColor="blue.100"
																	color="blue.500">
																	<CircularProgressLabel fontWeight="bold">
																		{Math.round(
																			(summary.lesson.completed /
																				(summary.lesson.pending +
																					summary.lesson.completed)) *
																				100
																		)}
																		{__('%', 'masteriyo')}
																	</CircularProgressLabel>
																</CircularProgress>
																<Stack direction="column" spacing="1">
																	<Text
																		textTransform="uppercase"
																		fontSize="x-small"
																		color="gray.500"
																		fontWeight="bold">
																		{__('Lesson', 'masteriyo')}
																	</Text>
																	<Text
																		fontSize="x-small"
																		fontWeight="bold"
																		color="blue.500">
																		{summary.lesson.completed}
																		{__(' Completed', 'masteriyo')}
																	</Text>
																	<Text
																		fontSize="x-small"
																		fontWeight="bold"
																		color="gray.700">
																		{summary.lesson.pending}
																		{__(' Left', 'masteriyo')}
																	</Text>
																</Stack>
															</Stack>
														</ListItem>
														<ListItem pt="6">
															<Stack direction="row" spacing="2">
																<CircularProgress
																	value={Math.round(
																		(summary.quiz.completed /
																			(summary.quiz.pending +
																				summary.quiz.completed)) *
																			100
																	)}
																	size="54px"
																	capIsRound
																	trackColor="gray.100"
																	color="blue.500">
																	<CircularProgressLabel fontWeight="bold">
																		{Math.round(
																			(summary.quiz.completed /
																				(summary.quiz.pending +
																					summary.quiz.completed)) *
																				100
																		)}
																		{__('%', 'masteriyo')}
																	</CircularProgressLabel>
																</CircularProgress>
																<Stack direction="column" spacing="1">
																	<Text
																		textTransform="uppercase"
																		fontSize="x-small"
																		color="gray.500"
																		fontWeight="bold">
																		{__('Quiz', 'masteriyo')}
																	</Text>
																	<Text
																		fontSize="x-small"
																		fontWeight="bold"
																		color="blue.500">
																		{summary.quiz.completed}
																		{__(' Completed', 'masteriyo')}
																	</Text>
																	<Text
																		fontSize="x-small"
																		fontWeight="bold"
																		color="gray.700">
																		{summary.quiz.pending}
																		{__(' Left', 'masteriyo')}
																	</Text>
																</Stack>
															</Stack>
														</ListItem>
													</List>
												</PopoverContent>
											</Popover>
										</Stack>
									</Stack>
									<Progress
										value={summary.total.completed}
										size="xs"
										rounded="full"
										max={summary.total.completed + summary.total.pending}
									/>
								</Stack>
							</Stack>

							<Stack
								direction="row"
								spacing="3"
								align="center"
								color="gray.400">
								<AvatarMenu />
							</Stack>
						</Stack>
					</Container>
				</Box>
			</Slide>
		</Box>
	);
};

export default Header;
