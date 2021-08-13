import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Flex,
	Image,
	Link,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiShow } from 'react-icons/bi';
import { Link as RouterLink } from 'react-router-dom';
import { Logo } from '../../constants/images';
import routes from '../../constants/routes';

interface Props {
	courseId: number;
	previewUrl?: string;
	onSave?: () => void;
	isSaveLoading?: boolean;
}

const HeaderBuilder: React.FC<Props> = (props) => {
	const { courseId, previewUrl, onSave, isSaveLoading } = props;

	const navLinkStyles = {
		mr: '10',
		py: '6',
		d: 'flex',
		alignItems: 'center',
		fontWeight: 'medium',
		fontSize: 'sm',
		_hover: {
			color: 'blue.500',
			textDecor: 'none',
		},
	};

	const navActiveStyles = {
		borderBottom: '2px',
		borderColor: 'blue.500',
		color: 'blue.500',
	};

	return (
		<Box bg="white" w="full">
			<Container maxW="container.xl" bg="white">
				<Flex direction="row" justifyContent="space-between" align="center">
					<Stack direction="row" spacing="12" align="center">
						<Box>
							<RouterLink to={routes.courses.list}>
								<Image src={Logo} alt="Masteriyo Logo" w="120px" />
							</RouterLink>
						</Box>
					</Stack>

					<ButtonGroup>
						{previewUrl && (
							<Link href={previewUrl} isExternal>
								<Button variant="outline" leftIcon={<BiShow />}>
									{__('Preview', 'masteriyo')}
								</Button>
							</Link>
						)}

						{onSave && (
							<Button
								colorScheme="blue"
								onClick={onSave}
								isLoading={isSaveLoading}>
								{__('Save', 'masteriyo')}
							</Button>
						)}
					</ButtonGroup>
				</Flex>
			</Container>
		</Box>
	);
};

export default HeaderBuilder;
