import {
	Alert,
	AlertDescription,
	AlertIcon,
	AlertTitle,
	Box,
	Button,
	CircularProgress,
	CircularProgressLabel,
	Container,
	Flex,
	Heading,
	IconButton,
	Image,
	Link,
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
	useMediaQuery,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiChevronDown, BiInfoCircle } from 'react-icons/bi';
import MobileHidden from '../../back-end/components/common/MobileHidden';
import { Polygon } from '../../back-end/constants/images';
import { CourseProgressSummaryMap } from '../schemas';
import localized from '../utils/global';
import AvatarMenu from './AvatarMenu';

interface Props {
	summary: CourseProgressSummaryMap;
	isOpen: boolean;
	onToggle: () => void;
}

const Header: React.FC<Props> = (props) => {
	const { summary, isOpen, onToggle } = props;
	const [isLargerThan782] = useMediaQuery('(min-width: 782px)');

	const {
		onOpen: onProgressOpen,
		onClose: onProgressClose,
		isOpen: isProgressOpen,
	} = useDisclosure();

	const lessonProgress =
		summary.lesson.completed === 0 && summary.lesson.pending === 0
			? 0
			: Math.round(
					(summary.lesson.completed /
						(summary.lesson.pending + summary.lesson.completed)) *
						100
			  );
	const quizProgress =
		summary.quiz.completed === 0 && summary.quiz.pending === 0
			? 0
			: Math.round(
					(summary.quiz.completed /
						(summary.quiz.pending + summary.quiz.completed)) *
						100
			  );

	return (
		<>
			<Box as="header" h="84px">
				<Slide
					direction="top"
					in={isOpen}
					style={{
						zIndex: 999,
						height: !isOpen && isLargerThan782 ? '65px' : '54px',
					}}
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
									<Link href={localized.urls.home}>
										{'' != localized.logo ? (
											<Image src={localized.logo[0]} height="36px" />
										) : (
											<Text textAlign="center">{localized.siteTitle}</Text>
										)}
									</Link>
								</Box>

								<Stack
									direction="row"
									spacing="6"
									align="center"
									flex="1"
									justify="space-between">
									<Stack direction="column" spacing="2" flex="1">
										<Stack
											direction="row"
											justify="space-between"
											align="center">
											<Stack direction="row" alignItems="center">
												<Flex>
													<Heading fontSize="lg">
														{summary.total.completed === 0 &&
														summary.total.pending === 0
															? 0
															: Math.round(
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
											<MobileHidden>
												<Stack
													direction="row"
													fontSize="xs"
													fontWeight="medium"
													color="gray.600">
													<Text>
														{summary.total.pending}/
														{summary.total.completed + summary.total.pending}
														{__(' Left', 'masteriyo')} |{' '}
													</Text>

													<Text>
														{summary.lesson.pending}{' '}
														{__('lessons left', 'masteriyo')} |{' '}
													</Text>

													<Text>
														{summary.quiz.pending}{' '}
														{__('quiz left', 'masteriyo')}
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
																			value={lessonProgress}
																			size="54px"
																			capIsRound
																			color="primary.500">
																			<CircularProgressLabel fontWeight="bold">
																				{lessonProgress + __('%', 'masteriyo')}
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
																				color="primary.500">
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
																			value={quizProgress}
																			size="54px"
																			capIsRound
																			trackColor="gray.100"
																			color="primary.500">
																			<CircularProgressLabel fontWeight="bold">
																				{quizProgress + __('%', 'masteriyo')}
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
																				color="primary.500">
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
											</MobileHidden>
										</Stack>
										<Progress
											value={summary.total.completed}
											size="xs"
											rounded="full"
											max={summary.total.completed + summary.total.pending}
										/>
									</Stack>
								</Stack>

								{localized.isUserLoggedIn ? (
									<Stack
										direction="row"
										spacing="3"
										align="center"
										color="gray.400">
										<AvatarMenu />
									</Stack>
								) : null}
							</Stack>
						</Container>
					</Box>
				</Slide>
			</Box>
			{summary.total.completed ===
				summary.total.pending + summary.total.completed && (
				<Container centerContent maxW="container.xl">
					<Alert
						status="success"
						variant="top-accent"
						fontSize="sm"
						mt="4"
						mb="-1.5">
						<Stack
							direction={['column', 'column', 'row', 'row']}
							align={['left', 'left', 'center', 'center']}
							justify="space-between"
							w="full">
							<Stack direction={['column', 'column', 'column', 'row']}>
								<AlertTitle mr={2} display="flex" flexDir="row">
									<AlertIcon />
									{__('Congratulations!', 'masteriyo')}
								</AlertTitle>
								<AlertDescription>
									{__(
										'You have successfully completed this course.',
										'masteriyo'
									)}
								</AlertDescription>
							</Stack>
							<Link href={localized.urls.courses}>
								<Button size="sm">{__('Back to Course', 'masteriyo')}</Button>
							</Link>
						</Stack>
					</Alert>
				</Container>
			)}
		</>
	);
};

export default Header;
