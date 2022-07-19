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
import {
	courseFAQ,
	DownloadMaterial,
	Logo,
	Ribbon,
	Stripe,
	Woocommerce,
	whiteLabel
} from '../../constants/images';
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
			<Stack
				width="100%"
				h="auto"
				p="4"
				mt="6"
				mb="6"
				bg="linear-gradient(90.03deg, #4584FF 0.57%, #7761FF 99.97%)"
				color="white">
				<Stack
					direction={['column', 'column', 'column', 'row']}
					justifyContent="space-between">
					<Stack
						alignItems="center"
						direction={['column', 'column', 'column', 'row']}
						spacing="4">
						<Box pos="relative" w="250px">
							<Heading
								pos="absolute"
								fontSize="xs"
								color="white"
								left="24px"
								top="10px">
								{__('SPECIAL INTRODUCTORY OFFER', 'masteriyo')}
							</Heading>
							<Ribbon />
						</Box>
						<Stack display="flex" direction="row" alignItems="center">
							<Text fontSize="15px" fontWeight="400">
								{__('We are giving', 'masteriyo')}
							</Text>
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
							</Center>
							<Text fontSize="15px" fontWeight="400">
								{__('off on all our premium plans.', 'masteriyo')}
							</Text>
						</Stack>
					</Stack>
					<Stack direction="row" spacing="5" justifyContent="center">
						<Link
							href="https://masteriyo.com/wordpress-lms/pricing/"
							target="_blank">
							<Button color="black">{__('Buy now', 'masteriyo')}</Button>
						</Link>
						<Link
							href="https://masteriyo.com/wordpress-lms/contact/"
							target="_blank">
							<Button
								variant="unstyled"
								textDecor="underline"
								color="rgba(255,255,255,0.8)"
								_hover={{ color: 'white' }}>
								{__('Any Queries?', 'masteriyo')}
							</Button>
						</Link>
					</Stack>
				</Stack>
			</Stack>
			<Container maxW="container.xl">
				<Row>
					<Col md={3.3}>
						<AddonItem
							addOnName={__('Stripe Payment Gateway', 'masteriyo')}
							addOnDescription={__(
								'Easily sell online courses and accept credit card payments via Stripe. It supports major cards like Visa, MasterCard, American Express, Discover, debit cards, etc.',
								'masteriyo'
							)}
							thumbnailSrc={Stripe}
						/>
					</Col>
					<Col md={3.3}>
						<AddonItem
							addOnName={__('Download Materials', 'masteriyo')}
							addOnDescription={__(
								'Attach unlimited lesson materials such as PDF, Doc, etc. Provide students with quick and easy access to download materials, and preview without download.',
								'masteriyo'
							)}
							thumbnailSrc={DownloadMaterial}
						/>
					</Col>
					<Col md={3.3}>
						<AddonItem
							addOnName={__('WooCommerce Integration', 'masteriyo')}
							addOnDescription={__(
								'WooCommerce Integration for Masteriyo allows to enroll users using WooCommerce checkout process and payment methods.',
								'masteriyo'
							)}
							thumbnailSrc={Woocommerce}
						/>
					</Col>
					<Col md={3.3}>
						<AddonItem
							addOnName={__('Course FAQ', 'masteriyo')}
							addOnDescription={__('Allows to add course FAQ.', 'masteriyo')}
							thumbnailSrc={courseFAQ}
						/>
					</Col>
					<Col md={3.3}>
						<AddonItem
							addOnName={__('White Label', 'masteriyo')}
							addOnDescription={__('Allows to add White Label.', 'masteriyo')}
							thumbnailSrc={whiteLabel}
						/>
					</Col>
				</Row>
			</Container>
		</Tabs>
	);
};

export default AddOns;
