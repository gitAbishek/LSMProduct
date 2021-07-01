import {
	Box,
	Button,
	CircularProgress,
	Container,
	Flex,
	Heading,
	Icon,
	IconButton,
	Image,
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
import { BiChevronDown, BiHeart, BiInfoCircle, BiSearch } from 'react-icons/bi';
import { useQuery } from 'react-query';
import { useParams } from 'react-router-dom';
import { Logo, Polygon } from '../../back-end/constants/images';
import urls from '../../back-end/constants/urls';
import API from '../../back-end/utils/api';
import AvatarMenu from './AvatarMenu';
import Notification from './Notification';

const Header = () => {
	const { courseId }: any = useParams();

	const {
		onOpen: onProgressOpen,
		onClose: onProgressClose,
		isOpen: isProgressOpen,
	} = useDisclosure();
	const progressAPI = new API(urls.interactiveProgress);

	const { isOpen, onToggle } = useDisclosure({
		defaultIsOpen: true,
	});

	const courseProgress = useQuery(
		[`courseProgress${courseId}`, courseId],
		() => progressAPI.store({ course_id: courseId }),
		{
			enabled: !!courseId,
		}
	);

	return (
		<Box as="header" h="84px">
			<Slide direction="top" in={isOpen} style={{ zIndex: 999 }}>
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
								<Image src={Logo} />
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
												<Heading fontSize="lg">35</Heading>
												<Text fontSize="xs">%</Text>
											</Flex>

											<Text
												fontSize="10px"
												textTransform="uppercase"
												color="gray.600"
												fontWeight="bold">
												Complete
											</Text>
										</Stack>
										<Stack
											direction="row"
											fontSize="xs"
											fontWeight="medium"
											color="gray.600">
											<Text>11/21 Steps | </Text>
											<Text>2 lessons left | </Text>
											<Text>1 Quiz left</Text>
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
												<PopoverContent p="8">
													<PopoverArrow />
													<CircularProgress value={60} />
												</PopoverContent>
											</Popover>
										</Stack>
									</Stack>
									<Progress value={80} size="xs" rounded="full" />
								</Stack>
								<Button
									colorScheme="blue"
									rounded="3xl"
									textTransform="uppercase"
									fontWeight="bold">
									{__('Submit Quiz', 'masteriyo')}
								</Button>
							</Stack>
							<Stack
								direction="row"
								spacing="3"
								align="center"
								w="120px"
								color="gray.400">
								<Icon as={BiSearch} />
								<Icon as={BiHeart} />
								<Notification />
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
