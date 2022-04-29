import {
	Box,
	Button,
	Center,
	Container,
	Heading,
	Image,
	Link,
	Stack,
	Tab,
	TabList,
	Tabs,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { Col, Row } from 'react-grid-system';
import { DownloadMaterial, Logo, Ribbon, Stripe } from '../../constants/images';
import AddonItem from './components/AddonItem';

const AddOns = () => {
	const [status, setStatus] = useState<string>('any');

	const tabStyles = {
		fontWeight: 'medium',
		fontSize: 'sm',
		py: '6',
		px: 0,
		mx: 4,
		_hover: {
			color: 'blue.500',
		},
	};

	return (
		<Tabs>
			<Stack direction="column" spacing="8" align="center">
				<Box bg="white" w="full" shadow="header" pb={['3', 0, 0]}>
					<Container maxW="container.xl">
						<Stack
							direction={['column', 'row']}
							justifyContent="space-between"
							align="center">
							<Stack
								direction={['column', null, 'row']}
								spacing={['3', null, '8']}
								align="center"
								minHeight="16">
								<Box d={['none', null, 'block']}>
									<Image src={Logo} w="36px" />
								</Box>
								<TabList borderBottom="none" bg="white">
									<Tab sx={tabStyles} onClick={() => setStatus('any')}>
										{__('All Addons', 'masteriyo')}
									</Tab>
								</TabList>
							</Stack>
						</Stack>
					</Container>
				</Box>
			</Stack>
			<Stack
				width="100%"
				h="auto"
				py="4"
				bg="linear-gradient(90.03deg, #4584FF 0.57%, #7761FF 99.97%)"
				color="white">
				<Container maxW="container.xl">
					<Stack direction="row" justifyContent="space-between">
						<Stack direction="row" spacing="4">
							<Box pos="relative" w="300px">
								<Heading
									pos="absolute"
									fontSize="md"
									color="white"
									left="16px"
									top="10px">
									{__('SPECIAL INTRODUCTORY OFFER', 'masteriyo')}
								</Heading>
								<Ribbon />
							</Box>

							<Text fontSize="15px" fontWeight="400">
								We are giving{' '}
								<Center
									d="inline-flex"
									w="40px"
									h="40px"
									bg="rgba(255,255,255, 0.5)"
									fontWeight="bold"
									rounded="full">
									75
									<Text as="span" fontSize="xx-small">
										%
									</Text>
								</Center>{' '}
								off on our all premium plans
							</Text>
						</Stack>
						<Stack direction="row" spacing="5">
							<Button color="black">Buy now</Button>
							<Link>
								<Button
									variant="unstyled"
									textDecor="underline"
									color="rgba(255,255,255,0.8)"
									_hover={{ color: 'white' }}>
									Any Queries?
								</Button>
							</Link>
						</Stack>
					</Stack>
				</Container>
			</Stack>
			<Stack width="container.xl" ml="auto" mr="auto" mt="10">
				<Row>
					<Col md={3}>
						<AddonItem
							addOnName={__('Stripe', 'masteriyo')}
							addOnDescription={__(
								'Easily sell online courses and accept credit card payments via Stripe. It supports major cards like Visa, MasterCard, American Express, Discover, debit cards, etc.',
								'masteriyo'
							)}
							thumbnailSrc={Stripe}
						/>
					</Col>
					<Col md={3}>
						<AddonItem
							addOnName={__('Download Materials', 'masteriyo')}
							addOnDescription={__(
								'Download materials description',
								'masteriyo'
							)}
							thumbnailSrc={DownloadMaterial}
						/>
					</Col>
				</Row>
			</Stack>
		</Tabs>
	);
};

export default AddOns;
