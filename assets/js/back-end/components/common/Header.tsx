import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Flex,
	Heading,
	Image,
	Stack,
} from '@chakra-ui/react';
import React from 'react';
import { Link } from 'react-router-dom';
import { Logo } from '../../constants/images';
import routes from '../../constants/routes';

interface Props {
	firstBtn?: {
		label: string;
		action: () => void;
		isDisabled?: boolean;
		isLoading?: boolean;
	};
	secondBtn?: {
		label: string;
		action: () => void;
		isDisabled?: boolean;
		isLoading?: boolean;
	};
	thirdBtn?: {
		label: string;
		action: () => void;
		isDisabled?: boolean;
		isLoading?: boolean;
	};
	hasLink?: boolean;
	course?: {
		name: string;
		id: number;
	};
}

const Header: React.FC<Props> = (props) => {
	const { firstBtn, secondBtn, thirdBtn, hasLink, course, children } = props;

	const navLinkStyles = {
		mr: '10',
		py: '6',
		d: 'flex',
		alignItems: 'center',
		fontWeight: 'medium',
		fontSize: 'sm',
	};

	const navActiveStyles = {
		borderBottom: '2px',
		borderColor: 'blue.500',
		color: 'blue.500',
	};

	return (
		<Box bg="white" w="full" shadow="header">
			<Container maxW="container.xl" bg="white">
				<Flex direction="row" justifyContent="space-between" align="center">
					<Stack direction="row" spacing="8" align="center" minHeight="16">
						<Link to={routes.courses.list}>
							<Image src={Logo} h="30px" />
						</Link>
						{course && (
							<Heading fontSize="md" fontWeight="medium">
								{course.name}
							</Heading>
						)}

						{children}
					</Stack>

					<ButtonGroup>
						{firstBtn && (
							<Button
								variant="outline"
								onClick={firstBtn.action}
								isLoading={firstBtn.isLoading}
								isDisabled={firstBtn.isDisabled}>
								{firstBtn.label}
							</Button>
						)}

						{secondBtn && (
							<Button
								variant="outline"
								colorScheme="blue"
								onClick={secondBtn.action}
								isDisabled={secondBtn.isDisabled}
								isLoading={secondBtn.isLoading}>
								{secondBtn.label}
							</Button>
						)}

						{thirdBtn && (
							<Button
								colorScheme="blue"
								onClick={thirdBtn.action}
								isDisabled={thirdBtn.isDisabled}
								isLoading={thirdBtn.isLoading}>
								{thirdBtn.label}
							</Button>
						)}
					</ButtonGroup>
				</Flex>
			</Container>
		</Box>
	);
};

export default Header;
