import {
	Avatar,
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
import {
	BiBell,
	BiDownArrow,
	BiHeart,
	BiInfoCircle,
	BiSearch,
} from 'react-icons/bi';
import { __ } from '@wordpress/i18n';

const Header = () => {
	const {
		onOpen: onProgressOpen,
		onClose: onProgressClose,
		isOpen: isProgressOpen,
	} = useDisclosure();

	const { isOpen: isHeaderOpen, onToggle: onHeaderToggle } = useDisclosure();

	return (
		<>
			<IconButton
				onClick={onHeaderToggle}
				icon={<BiDownArrow />}
				aria-label="toggle header"
			/>
			<Slide direction="top" in={isHeaderOpen} style={{ zIndex: 999 }}>
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
							<Icon as={BiBell} />
							<Avatar size="sm" />
						</Stack>
					</Stack>
				</Container>
			</Slide>
		</>
	);
};

export default Header;
