import {
	Box,
	Button,
	CircularProgress,
	Container,
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
import React from 'react';
import { Logo } from '../../back-end/constants/images';
import { BiChevronDown, BiHeart, BiInfoCircle, BiSearch } from 'react-icons/bi';
import { __ } from '@wordpress/i18n';
import { Polygon } from '../../back-end/constants/images';
import AvatarMenu from './AvatarMenu';
import Notification from './Notification';

const Header = () => {
	const {
		onOpen: onProgressOpen,
		onClose: onProgressClose,
		isOpen: isProgressOpen,
	} = useDisclosure();

	const { isOpen, onToggle } = useDisclosure({
		defaultIsOpen: true,
	});

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
							spacing="4"
							align="center"
							py="4"
							justifyContent="space-between">
							<Image src={Logo} maxW="180px" />

							<Stack direction="row" spacing="6" align="center">
								<Stack direction="column" spacing="1">
									<Stack direction="row" justify="space-between" align="center">
										<Stack direction="row">
											<Heading>35%</Heading>
											<Text>Complete</Text>
										</Stack>
										<Stack direction="row">
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
									<Progress value={80} size="xs" />
								</Stack>
								<Button
									colorScheme="blue"
									rounded="3xl"
									textTransform="uppercase"
									fontWeight="bold">
									{__('Submit Quiz', 'masteriyo')}
								</Button>
							</Stack>
							<Stack direction="row" spacing="2" align="center">
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
